<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AuctionWinnerExport;
use App\Http\Controllers\Controller;
use App\Models\AuctionWinner;
use App\Models\Cart;
use App\Models\Event;
use App\Models\EventFish;
use App\Models\LogBid;
use App\Models\LogBidDetail;
use App\Models\Member;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AuctionWinnerController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');
        $now = Carbon::now();

        if ($this->request->ajax()) {
            $winners = AuctionWinner::query()
                ->join('t_log_bidding', 't_pemenang_lelang.id_bidding', '=', 't_log_bidding.id_bidding')
                ->join('m_ikan_lelang', 't_log_bidding.id_ikan_lelang', '=', 'm_ikan_lelang.id_ikan')
                ->select(
                    'id_pemenang_lelang',
                    'status_pembayaran',
                    'm_ikan_lelang.id_event as id_event',
                    't_log_bidding.id_peserta as id_peserta',
                    't_pemenang_lelang.id_bidding'
                )
                ->with(['bidding.member.city', 'event'])
                ->where('t_pemenang_lelang.status_aktif', 1)
                ->orderBy('m_ikan_lelang.id_event', 'desc')
                ->get()
                ->mapWithKeys(fn($q) => [$q['id_peserta'].$q['id_event'] => $q]);

            return DataTables::of($winners)
            ->addIndexColumn()
            ->addColumn('tgl_mulai_formatted', function($row) {
                return $row->event && $row->event->tgl_mulai ? 
                    \Carbon\Carbon::parse($row->event->tgl_mulai)->format('d-m-Y H:i') : 
                    '';
            })
            ->addColumn('action','admin.pages.auction-winner.dt-action')
            ->rawColumns(['action'])
            ->make(true);
        }

        $auctionProducts = EventFish::
        doesntHave('winners')
        ->whereHas('event', function($q) use ($now){
            $q->where('tgl_akhir', '<', $now);
        })
        ->with(['bids.member', 'maxBid', 'event'])
        ->where('status_aktif', 1)->get()
        ->mapWithKeys(fn($a) => [$a->id_ikan => $a]);

        $fishInWinner = AuctionWinner::whereIn('id_bidding', $auctionProducts->pluck('maxBid.id_bidding'))
            ->get()
            ->mapWithKeys(fn($q)=>[$q->id_bidding => $q]);

        foreach ($auctionProducts as $cProduct) {
            if ($cProduct->maxBid === null) {
                continue;
            }

            $dateDiff = Carbon::parse($now, 'id')->diffInMinutes($cProduct->maxBid->updated_at);

            $dateEventEnd = Carbon::parse($cProduct->event->tgl_akhir)->addMinutes($cProduct->extra_time);

            if ($now < $dateEventEnd) {
                continue;
            }

            if ($dateDiff < $cProduct->extra_time || array_key_exists($cProduct->maxBid->id_bidding, $fishInWinner->toArray())) {
                continue;
            }

            $data['id_bidding'] = $cProduct->maxBid->id_bidding;
            $data['create_by'] = Auth::guard('admin')->id();
            $data['update_by'] = Auth::guard('admin')->id();
            $data['status_aktif'] = 1;

            AuctionWinner::create($data);
        }

        return view('admin.pages.auction-winner.index')->with([
            'type_menu' => 'manage-auction-winner',
            'auctionProducts' => $auctionProducts,
        ]);
    }

    public function dynamicIndex()
    {
        if ($this->request->ajax()) {
            $fishes = EventFish::with(['maxBid.member', 'maxBid.latestDetail', 'event'])
                ->whereHas('event', fn($q) => $q->where('tgl_akhir', '<', Carbon::now()))
                ->whereHas('maxBid')
                ->where('status_aktif', 1)
                ->orderBy('id_ikan', 'desc');

            return DataTables::of($fishes)
                ->addIndexColumn()
                ->addColumn('no_ikan', fn($row) => $row->no_ikan ?? '-')
                ->addColumn('variety', fn($row) => $row->variety ?? '-')
                ->addColumn('pemenang', fn($row) => $row->maxBid?->member?->nama ?? '-')
                ->addColumn('no_hp', fn($row) => $row->maxBid?->member?->no_hp ?? '-')
                ->addColumn('nominal', fn($row) => $row->maxBid
                    ? 'Rp. ' . number_format($row->maxBid->nominal_bid, 0, '.', '.')
                    : '-')
                ->addColumn('tipe_bid', fn($row) => ($row->maxBid?->latestDetail?->status_bid === 1)
                    ? '<span class="badge badge-warning">Auto Bid</span>'
                    : '')
                ->addColumn('event_name', fn($row) => $row->event?->kategori_event ?? '-')
                ->addColumn('tgl_akhir', fn($row) => $row->event
                    ? Carbon::parse($row->event->tgl_akhir)->format('d M Y H:i')
                    : '-')
                ->addColumn('aksi', fn($row) =>
                    '<button class="btn btn-sm btn-primary btn-detail" data-id="' . $row->id_ikan . '">
                        <i class="fas fa-eye"></i> Detail
                    </button>'
                )
                ->rawColumns(['tipe_bid', 'aksi'])
                ->make(true);
        }

        return view('admin.pages.auction-winner.dynamic-index', [
            'type_menu' => 'manage-dynamic-winner',
        ]);
    }

    public function winnerPerUser()
    {
        if ($this->request->ajax()) {
            $fishes = EventFish::with(['maxBid.member', 'event'])
                ->whereHas('event', fn($q) => $q->where('tgl_akhir', '<', Carbon::now()))
                ->whereHas('maxBid')
                ->where('status_aktif', 1)
                ->get();

            $grouped = $fishes
                ->groupBy(fn($fish) => $fish->maxBid->id_peserta . '_' . $fish->id_event)
                ->map(function ($fishGroup) {
                    $first   = $fishGroup->first();
                    $member  = $first->maxBid->member;
                    $event   = $first->event;
                    return [
                        'id_peserta'  => $first->maxBid->id_peserta,
                        'id_event'    => $first->id_event,
                        'nama'        => $member?->nama ?? '-',
                        'no_hp'       => $member?->no_hp ?? '-',
                        'event_name'  => $event?->kategori_event ?? '-',
                        'tgl_event'   => $event
                            ? Carbon::parse($event->tgl_mulai)->format('d M Y') . ' — ' . Carbon::parse($event->tgl_akhir)->format('d M Y')
                            : '-',
                        'jumlah_ikan' => $fishGroup->count(),
                    ];
                })
                ->sortByDesc('id_event')
                ->values();

            return DataTables::of($grouped)
                ->addIndexColumn()
                ->addColumn('aksi', fn($row) =>
                    '<button class="btn btn-sm btn-primary btn-detail-user"
                        data-peserta="' . $row['id_peserta'] . '"
                        data-event="' . $row['id_event'] . '">
                        <i class="fas fa-eye"></i> Detail
                    </button>'
                )
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return view('admin.pages.auction-winner.winner-per-user', [
            'type_menu' => 'manage-winner-per-user',
        ]);
    }

    public function winnerPerUserDetail($idPeserta, $idEvent)
    {
        $member = Member::with('city')->findOrFail($idPeserta);
        $event  = Event::findOrFail($idEvent);

        $fishes = EventFish::with(['maxBid.latestDetail'])
            ->where('id_event', $idEvent)
            ->where('status_aktif', 1)
            ->get()
            ->filter(fn($fish) => $fish->maxBid?->id_peserta == $idPeserta)
            ->map(fn($fish) => [
                'no_ikan'     => $fish->no_ikan ?? '-',
                'variety'     => $fish->variety ?? '-',
                'nominal_bid' => 'Rp. ' . number_format($fish->maxBid->nominal_bid, 0, '.', '.'),
                'waktu_bid'   => $fish->maxBid->waktu_bid
                    ? Carbon::parse($fish->maxBid->waktu_bid)->format('d M Y H:i:s')
                    : '-',
                'is_auto'     => $fish->maxBid->latestDetail?->status_bid === 1,
            ]);

        return response()->json([
            'member' => [
                'nama'  => $member->nama ?? '-',
                'no_hp' => $member->no_hp ?? '-',
                'email' => $member->email ?? '-',
                'kota'  => $member->city?->name ?? '-',
            ],
            'event' => [
                'name'      => $event->kategori_event ?? '-',
                'tgl_mulai' => Carbon::parse($event->tgl_mulai)->format('d M Y'),
                'tgl_akhir' => Carbon::parse($event->tgl_akhir)->format('d M Y'),
            ],
            'fishes' => $fishes,
        ]);
    }

    public function dynamicWinnerDetail($idIkan)
    {
        $fish = EventFish::with(['maxBid.member.city', 'maxBid.latestDetail', 'event'])
            ->where('id_ikan', $idIkan)
            ->where('status_aktif', 1)
            ->firstOrFail();

        $history = LogBidDetail::with('logBid.member')
            ->whereHas('logBid', fn($q) => $q->where('id_ikan_lelang', $idIkan)->where('status_aktif', 1))
            ->where('status_aktif', 1)
            ->orderBy('nominal_bid', 'desc')
            ->orderByDesc('id_bidding_detail')
            ->get()
            ->map(fn($detail) => [
                'nama'        => $detail->logBid?->member?->nama ?? '-',
                'no_hp'       => $detail->logBid?->member?->no_hp ?? '-',
                'nominal_bid' => 'Rp. ' . number_format($detail->nominal_bid, 0, '.', '.'),
                'waktu_bid'   => $detail->created_at ? Carbon::parse($detail->created_at)->format('d M Y H:i:s') : '-',
                'is_winner'   => $fish->maxBid && $fish->maxBid->id_bidding === $detail->id_bidding,
                'is_auto'     => $detail->status_bid === 1,
            ]);

        $winner = $fish->maxBid?->member;

        return response()->json([
            'fish' => [
                'no_ikan'    => $fish->no_ikan ?? '-',
                'variety'    => $fish->variety ?? '-',
                'event_name' => $fish->event?->kategori_event ?? '-',
                'tgl_akhir'  => $fish->event ? Carbon::parse($fish->event->tgl_akhir)->format('d M Y H:i') : '-',
            ],
            'winner' => $winner ? [
                'nama'     => $winner->nama ?? '-',
                'no_hp'    => $winner->no_hp ?? '-',
                'email'    => $winner->email ?? '-',
                'kota'     => $winner->city?->name ?? '-',
                'nominal'  => 'Rp. ' . number_format($fish->maxBid->nominal_bid, 0, '.', '.'),
                'is_auto'  => $fish->maxBid->latestDetail?->status_bid === 1,
            ] : null,
            'history' => $history,
        ]);
    }

    public function store()
    {
        $data = $this->request->only(['id_bidding']);

        $data['create_by'] = Auth::guard('admin')->id();
        $data['update_by'] = Auth::guard('admin')->id();
        $data['status_aktif'] = 1;

        $dataCart['create_by'] = Auth::guard('admin')->id();
        $dataCart['update_by'] = Auth::guard('admin')->id();
        $dataCart['status_aktif'] = 1;

        $createWinner = AuctionWinner::create($data);
        $createWinner->load('bidding');
        $dataCart['id_peserta'] = $createWinner->bidding->id_peserta;
        $dataCart['cartable_id'] = $createWinner->bidding->id_ikan_lelang;
        $dataCart['cartable_type'] = Cart::EventFish;

        Cart::create($dataCart);

        if($createWinner){
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Pemenang Lelang',

            ],200);
        }else{
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Pemenang Lelang'
            ],500);
        }
    }

    public function show($id)
    {
        $fish = AuctionWinner::findOrFail($id);

        if($fish){
            return response()->json($fish);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ],404);
        }
    }

    public function update($id)
    {
        $fish = AuctionWinner::findOrFail($id);
        $data = $this->request->all();
        $validator = Validator::make($this->request->all(), [
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data['update_by'] = Auth::guard('admin')->id();

        $image = $fish->foto_ikan;
        if($this->request->hasFile('path_foto')){
            $image = $this->request->file('path_foto')->store(
                'foto_champion_koi','public'
            );
        }

        try {

            $data['foto_ikan'] = $image;
            unset($data['path_foto']);
            $fish->update($data);

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data Pemenang Lelang',
                    'type' => 'success'
                ],
            ],200);

        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function destroy($id)
    {
        $fish = AuctionWinner::findOrFail($id);
        $fish->status_aktif = 0;

        $fish->save();

        return response()->json([
            'success' => true,
        ],200);
    }

    public function info()
    {
        $idPeserta = $this->request->id_peserta;
        $idEvent = $this->request->id_event;

        $orderDetail = AuctionWinner::whereHas('bidding', function($q) use($idPeserta, $idEvent){
            $q->where('id_peserta', $idPeserta)
                ->whereHas('eventFish', fn($q2) => $q2->where('id_event', $idEvent))
            ;
        })
        ->with('bidding.eventFish.photo')
        ->get();

        $member = Member::with(['city', 'province'])->findOrFail($idPeserta);

        return response()->json([
            'details' => $orderDetail,
            'member' => $member,
        ]);
    }

    public function excels()
    {
        return Excel::download(new AuctionWinnerExport, 'data_pemenang_lelang.xlsx');
    }

    public function winnerUpdate()
    {
        $idPeserta = $this->request->id_peserta;
        $idEvent = $this->request->id_event;
        $status = $this->request->status;

        $orderDetail = AuctionWinner::whereHas('bidding', function($q) use($idPeserta, $idEvent){
            $q->where('id_peserta', $idPeserta)
                ->whereHas('eventFish', fn($q2) => $q2->where('id_event', $idEvent))
            ;
        })
        ->with('bidding.eventFish')
        ->get();

        try {

            DB::transaction(function () use ($orderDetail, $idPeserta, $status){
                
                if ($status != 1) {
                    AuctionWinner::whereIn('id_pemenang_lelang', $orderDetail->pluck('id_pemenang_lelang'))
                    ->update(['status_pembayaran' => 1]);

                    $eventFishIds = $orderDetail->pluck('bidding.id_ikan_lelang');

                    Cart::where('id_peserta', $idPeserta)
                        ->whereIn('cartable_id', $eventFishIds)
                        ->where('cartable_type', Cart::EventFish)
                        ->update(['status_aktif' => 0]);
                }else {
                    AuctionWinner::whereIn('id_pemenang_lelang', $orderDetail->pluck('id_pemenang_lelang'))
                    ->update(['status_pembayaran' => 0]);

                    $eventFishIds = $orderDetail->pluck('bidding.id_ikan_lelang');

                    Cart::where('id_peserta', $idPeserta)
                        ->whereIn('cartable_id', $eventFishIds)
                        ->where('cartable_type', Cart::EventFish)
                        ->update(['status_aktif' => 1]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data Pemenang Lelang',
                    'type' => 'success'
                ],
            ],200);

        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
            ],500);
        }
    }
}
