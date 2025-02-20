<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\EventFish;
use App\Models\Notification;
use App\Models\AuctionWinner;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(Request $request){
        $this->request = $request;
    }
    
    public function index()
    {
        if ($this->request->ajax()) {
            $auctions = Event::query()
                ->where('status_aktif', 1)
                ->orderBy('created_at', 'desc');

            return DataTables::of($auctions)
            ->addIndexColumn()
            ->editColumn('banner', function ($data) {
                $path = $data->banner ?? false;

                if (!$path) {
                    return '';
                }

                return '
                    <img src="'.asset("storage/$path").'" style="
                        max-width: 400px;
                        max-height: 150px;
                        width: auto;
                        height: auto;
                        object-fit: contain;
                    ">
                ';
            })
            ->editColumn('total_hadiah', function ($data) {
                $number = number_format( $data->total_hadiah , 0 , '.' , '.' );

                return $number;
            })
            ->editColumn('rules_event', function ($data) {
                $maxLength = 150; // Batas karakter rules_event
                $rules_event = strip_tags($data->rules_event); // Hilangkan tag HTML

                if (strlen($rules_event) > $maxLength) {
                    $rules_event = substr($rules_event, 0, $maxLength) . '...';
                }

                return $rules_event;
            })
            ->addColumn('text_status_tutup', function ($data) {
                $text = "Ya";

                if ($data->status_tutup === 0) {
                    $text = "Tidak";
                }

                return $text;
            })
            ->addColumn('action','admin.pages.auction.dt-action')
            ->rawColumns(['action', 'banner', 'rules_event', 'text_status_tutup'])
            ->make(true);
        }

        $auctionProducts = EventFish::where('status_aktif', 1)->get();
        $auctionProductsNoEvent = $auctionProducts->whereNull('id_event');

        return view('admin.pages.auction.index')->with([
            'type_menu' => 'manage-auction',
            'auctionProducts' => $auctionProducts,
            'auctionProductsNoEvent' => $auctionProductsNoEvent
        ]);
    }

    public function store()
    {
        $data = $this->request->all();

        $data['create_by'] = Auth::guard('admin')->id();
        $data['update_by'] = Auth::guard('admin')->id();
        $data['total_hadiah'] = (int) str_replace('.', '', $data['total_hadiah']);
        $data['status_aktif'] = 1;

        $image = null;
        if($this->request->hasFile('banner')){
            $image = $this->request->file('banner')->store(
                'foto_auction','public'
            );
        }

        $data['banner'] = $image;

        $auctionProductIds = $data['auction_products'];
        unset($data['auction_products']);

        $createAuction = Event::create($data);

        EventFish::whereIn('id_ikan', $auctionProductIds)->update([
            'id_event' => $createAuction->id_event
        ]);

        if($createAuction){
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Auction',

            ],200);
        }else{
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Auction'
            ],500);
        }
    }

    public function show($id)
    {
        $auction = Event::with('auctionProducts.photo')->findOrFail($id);

        if($auction){
            return response()->json($auction);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ],404);
        }
    }

    public function update($id)
    {
        $action = $this->request->input('action', null);
        $auction = Event::With('auctionProducts')->findOrFail($id);

        if ($action === 'close-auction') {
            $auction->status_tutup = 1;
            $auction->save();

            $now = Carbon::now();

            $auctionProducts = EventFish::doesntHave('winners')
                ->whereHas('event', function ($q) use ($now) {
                    $q->where('tgl_akhir', '<', $now);
                })
                ->with(['bids.member', 'maxBid', 'event'])
                ->where('status_aktif', 1)
                ->get()
                ->mapWithKeys(fn($a) => [$a->id_ikan => $a]);

            $fishInWinner = AuctionWinner::whereIn('id_bidding', $auctionProducts->pluck('maxBid.id_bidding'))
                ->get()
                ->mapWithKeys(fn($q) => [$q->id_bidding => $q]);

            // Array untuk menampung response dari notifikasi
            $notificationResponses = [];

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

                $data = [
                    'id_bidding' => $cProduct->maxBid->id_bidding,
                    'create_by' => Auth::guard('admin')->id(),
                    'update_by' => Auth::guard('admin')->id(),
                    'status_aktif' => 1,
                ];

                AuctionWinner::create($data);

                $winner = $cProduct->maxBid->member;
                $fishVariety = "{$cProduct->no_ikan} | {$cProduct->variety} | {$cProduct->breeder} | {$cProduct->bloodline} | {$cProduct->sex}";
                $finalBidPrice = $cProduct->maxBid->nominal_bid;

                $notification = Notification::create([
                    'peserta_id' => $winner->id_peserta,
                    'label' => 'Auction Winner',
                    'description' => "Selamat kepada Mr. / Ms. {$winner->nama} telah memenangkan Lelang Koi {$fishVariety} dengan nilai final bid Rp " . number_format($finalBidPrice, 0, ',', '.'),
                    'link' => route('winning-auction'),
                ]);

                if($notification) {
                    $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
                    $token = env('QONTAK_API_KEY');

                    $phoneNumber = $winner->no_hp;
                    $phoneNumber = preg_replace('/\D/', '', $phoneNumber);
                    if (strpos($phoneNumber, '0') === 0) {
                        $phoneNumber = '62' . substr($phoneNumber, 1);
                    } else if (strpos($phoneNumber, '62') !== 0){
                        $phoneNumber = '62' . $phoneNumber;
                    }

                    $data = [
                        "to_name" => $winner->nama,
                        "to_number" => $phoneNumber,
                        "message_template_id" => "2c9c5f12-4578-4d36-9df9-b9296e9e9af2",
                        "channel_integration_id" => env('QONTAK_CHANNEL_INTEGRATION_ID'),
                        "language" => [
                            "code" => "id",
                        ],
                        "parameters" => [
                            "header" => [
                                "format" => "DOCUMENT",
                                "params" => [],
                            ],
                            "body" => [
                                [
                                    "key" => "0",
                                    "value_text" => $winner->nama,
                                    "value" => "customer_name",
                                ],
                                [
                                    "key" => "1",
                                    "value_text" => $fishVariety,
                                    "value" => "fish_variety",
                                ],
                                [
                                    "key" => "2",
                                    "value_text" => $finalBidPrice,
                                    "value" => "final_bid_price",
                                ],
                            ],
                            "buttons" => []
                        ]
                    ];

                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json',
                    ])->post($url, $data);


                    if ($response->successful()) {
                    $notificationResponses[] = [
                        'success' => true,
                        'message' => 'Broadcast berhasil dikirim ke: ' . $winner->nama,
                        'data' => $response->json(),
                        ];
                    } else {
                        $notificationResponses[] = [
                            'success' => false,
                            'message' => 'Broadcast gagal dikirim ke: ' . $winner->nama,
                            'error' => $response->body(),
                            'status' => $response->status(),
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data auction dan menentukan pemenang lelang',
                    'type' => 'success',
                ],
                'notification_responses' => $notificationResponses,
            ], 200);
        }

        $data = $this->request->all();
        $data['total_hadiah'] = (int) str_replace('.', '', $data['total_hadiah']);
        $validator = Validator::make($this->request->all(), [

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data['update_by'] = Auth::guard('admin')->id();
        $existsProductIds = $auction->auctionProducts->pluck('id_ikan')->toArray();
        $auctionProductIds = $data['edit_auction_products'];
        $removeProductIds = array_diff($existsProductIds, $auctionProductIds);
        unset($data['edit_auction_products']);

        $image = $auction->banner;
        if($this->request->hasFile('banner')){
            $image = $this->request->file('banner')->store(
                'foto_auction','public'
            );
        }

        $data['banner'] = $image;

        try {
            DB::transaction(function () use ($data, $auction, $auctionProductIds, $removeProductIds){
                $auction->update($data);

                EventFish::whereIn('id_ikan', $auctionProductIds)->update([
                    'id_event' => $auction->id_event
                ]);

                //removed item
                EventFish::whereIn('id_ikan', $removeProductIds)->update([
                    'id_event' => null
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => [
                    'title' => 'Berhasil',
                    'content' => 'Mengubah data auction',
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
        $auction = Event::findOrFail($id);
        $auction->status_aktif = 0;

        $auction->save();

        return response()->json([
            'success' => true,
        ],200);
    }
}
