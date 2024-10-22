<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AuctionWinnerExport;
use App\Http\Controllers\Controller;
use App\Models\AuctionWinner;
use App\Models\Cart;
use App\Models\Event;
use App\Models\EventFish;
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
                't_log_bidding.id_peserta as id_peserta'
                )
                ->with(['member.city', 'event'])
                ->where('t_pemenang_lelang.status_aktif', 1)
                ->orderBy('m_ikan_lelang.id_event', 'desc')
                ->get()
                ->mapWithKeys(fn($q) => [$q['id_peserta'].$q['id_event'] => $q]);
                ;

            return DataTables::of($winners)
            ->addIndexColumn()
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
