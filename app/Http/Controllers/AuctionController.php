<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\LogBid;
use App\Models\Member;
use App\Models\EventFish;
use App\Models\LogBidDetail;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AuctionController extends Controller
{
    public function index()
    {
        $auth = Auth::guard('member')->user();

        $now = Carbon::now();
        $nowAkhir = Carbon::now()->subDays(2)->endOfDay();

        $currentAuctions = Event::when($auth !== null, function ($q) use ($auth) {
            $q->with([
                'auctionProducts' => function ($q) {
                    $q->withCount('bidDetails')->with(['photo', 'maxBid', 'event', 'currency']);
                },
                'auctionProducts.wishlist' => fn ($w) => $w->where('id_peserta', $auth->id_peserta)
            ]);
        }, function ($q) {
            $q->with([
                'auctionProducts' => function ($q) {
                    $q->withCount('bidDetails')->with(['photo', 'maxBid', 'event']);
                },
            ]);
        })
            ->where('tgl_mulai', '<=', $now)
            ->where('tgl_akhir', '>=', $nowAkhir)
            ->where('status_aktif', 1)
            ->where('status_tutup', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($currentAuctions[0]->auctionProducts[0]->currency->symbol);

        $currentProducts = $currentAuctions
            ->pluck('auctionProducts')
            ->flatten(1)
            ->sortBy('no_ikan', SORT_NATURAL);

        $currentAuction = null;
        $currentTotalBid = 0;
        $currentTotalPrize = 0;
        if (count($currentProducts) > 0) {
            $now = Carbon::now()->toDateTimeString();

            foreach ($currentProducts as $product) {
                $currentTotalBid += $product->bid_details_count ?? 0;
                $currentTotalPrize += $product->maxBid->nominal_bid ?? 0;

                $product->tgl_akhir_extra_time = Carbon::createFromDate($product->event->tgl_akhir)
                    ->addMinutes($product->extra_time ?? 0)->toDateTimeString();

                if ($product->maxBid !== null && $product->maxBid->updated_at >= $product->event->tgl_akhir) {
                    $addedExtraTime2 = Carbon::createFromDate($product->maxBid->updated_at)
                        ->addMinutes($product->extra_time ?? 0)->toDateTimeString();

                    if ($product->tgl_akhir_extra_time < $addedExtraTime2) {
                        $product->tgl_akhir_extra_time = $addedExtraTime2;
                    }
                }
            }

            $auctionProducts = $currentProducts->where('tgl_akhir_extra_time', '>', $now);


            $currentAuction = $currentAuctions
                ->first();
        }

        Carbon::setLocale('id');

        $now = Carbon::now();

        return view('auction', [
            'auth' => $auth,
            'currentAuction' => $currentAuction,
            'auctionProducts' => $currentProducts,
            'now' => $now,
            'currentTotalBid' => $currentTotalBid,
            'currentTotalPrize' => $currentTotalPrize,
            'auctions' => $currentAuctions,
            'title' => 'auction'
        ]);
    }

    public function bid($idIkan)
    {
        $reqMaxBid = $this->request->input('request.max_bid', 0);

        if ($this->request->ajax()) {
        }

        if ($reqMaxBid === 1) {
        }

        $auth = Auth::guard('member')->user();

        $auctionProduct = EventFish::with(['photo', 'event'])->findOrFail($idIkan);

        $logBid = null;

        if ($auth) {
            $logBid = LogBid::where('id_peserta', $auth->id_peserta)->where('id_ikan_lelang', $idIkan)->first();
        }

        $maxBid = LogBid::where('id_ikan_lelang', $idIkan)->orderBy('nominal_bid', 'desc')->first()->nominal_bid ?? $auctionProduct->ob;

        $autoBid = 0;

        if ($logBid) {
            $nominalBid = $logBid->nominal_bid ?? 0;
            $maxBid = $nominalBid > $maxBid ? $nominalBid : $maxBid;
            $autoBid = $logBid->auto_bid;
        }

        $maxBidData = LogBidDetail::whereHas('logBid', function ($q) use ($idIkan) {
            $q->where('id_ikan_lelang', $idIkan);
        })
            ->with('logBid')
            ->orderBy('nominal_bid', 'desc')->first();

        Carbon::setLocale('id');

        $addedExtraTime = Carbon::createFromDate($auctionProduct->event->tgl_akhir)
            ->addMinutes($auctionProduct->extra_time ?? 0)
            ->toDateTimeString();
        // ->format('d M Y H:i:s');

        $now = Carbon::now()->toDateTimeString();

        if ($addedExtraTime < $now) {
            if ($maxBidData !== null && $maxBidData->logBid->updated_at >= $auctionProduct->event->tgl_akhir) {
                $addedExtraTime = Carbon::createFromDate($maxBidData->logBid->updated_at)
                    ->addMinutes($auctionProduct->extra_time ?? 0)
                    // ->format('d M Y H:i:s');
                    ->toDateTimeString();
            }
        }

        $now = Carbon::now()->toDateTimeString();

        return view('bid', [
            'auth' => $auth,
            'logBid' => $logBid,
            'autoBid' => (int) $autoBid,
            'maxBid' => (int) $maxBid,
            'idIkan' => $idIkan,
            'now' => $now,
            'auctionProduct' => $auctionProduct,
            'title' => 'auction',
            'addedExtraTime' => $addedExtraTime,
            'maxBidData' => $maxBidData,
            'currentPrice' => $maxBid,
        ]);
    }

    public function bidProcess($idIkan)
    {
        $auth = Auth::guard('member')->user();
        $auctionProduct = EventFish::with(['photo', 'event'])->findOrFail($idIkan);
        $nominalBid = $this->request->input('nominal_bid', null);
        $nominalBidDetail = $this->request->input('nominal_bid_detail', null);
        $autoBid = $this->request->input('auto_bid', null);

        $modKb = ($nominalBidDetail - $auctionProduct->ob) % $auctionProduct->kb === 0;
        if ($autoBid !== null) {
            $modAutoKb = $autoBid % $auctionProduct->kb === 0;
            if (!$modAutoKb) {
                return response()->json(['message' => 'Nominal auto bid harus sesuai dengan kelipatan bid'], 400);
            }
        }

        if (!$modKb && $autoBid === null) {
            return response()->json(['message' => 'Nominal bid harus sesuai dengan kelipatan bid'], 400);
        }

        $bids = LogBid::where('id_ikan_lelang', $idIkan)->orderBy('nominal_bid', 'desc')->first();

        // Proses Bid (logic yang anda punya sebelum if pengecekan waktu)
        $logBid = LogBid::where('id_peserta', $auth->id_peserta)->where('id_ikan_lelang', $idIkan)->first();
        $message = null;

        if ($logBid !== null) {
            $nominalBidDetail = (int) $nominalBid - (int) $logBid->nominal_bid;

            if ($autoBid <= $logBid->nominal_bid && $autoBid !== null) {
                $message = 'success updated';
                
            } else {
                $logBid->nominal_bid = $nominalBid;

                if ($autoBid !== null) {
                    $logBid->auto_bid = $autoBid;
                }

                $maxBid = $bids->nominal_bid ?? $auctionProduct->ob;
                if ((int) $nominalBid === (int) $maxBid) {
                    return response()->json(['message' => 'Nominal bid tidak sesuai'], 400);
                }

                if ((int)$nominalBid <= (int)$maxBid) {
                    return response()->json(['message' => 'Nominal tidak boleh dibawah harga saat ini'], 400);
                }

                $logBid->save();

                LogBidDetail::create([
                    'id_bidding' => $logBid->id_bidding,
                    'nominal_bid' => $logBid->nominal_bid,
                    'status_aktif' => 1,
                ]);

                $message = 'success updated';
            }
        } else {
            $maxBid = $bids->nominal_bid ?? $auctionProduct->ob;
            if ((int)$nominalBid === (int)$maxBid && $bids !== null) {
                return response()->json(['message' => 'Nominal bid tidak sesuai'], 400);
            }

            if ((int)$nominalBid <= (int) $maxBid && (int)$nominalBid !== (int)$auctionProduct->ob) {
                return response()->json(['message' => 'Nominal tidak boleh dibawah harga saat ini'], 400);
            }

            $createBid = LogBid::create([
                'id_ikan_lelang' => $idIkan,
                'id_peserta' => $auth->id_peserta,
                'nominal_bid' => $nominalBid,
                'auto_bid' => $autoBid,
                'waktu_bid' => Carbon::now(),
                'status_aktif' => 1,
            ]);

            LogBidDetail::create([
                'id_bidding' => $createBid->id_bidding,
                'nominal_bid' => $createBid->nominal_bid,
                'status_aktif' => 1,
            ]);

            if ($createBid) {
                $message = 'success created';
            } else {
                return response()->json(['message' => 'error', 500]);
            }
        }
        
        $notifData = [];
        
        // Cek waktu akhir event
        $auctionEndTime = $auctionProduct->event->tgl_akhir;
        $timeRemaining = Carbon::parse($auctionEndTime)->diffInMinutes(Carbon::now());
        
        // Kirim notifikasi jika dalam 15 menit terakhir dan belum berakhir
        if ($timeRemaining <= 15 && $timeRemaining > 0) {
            // Dapatkan semua bidder untuk ikan ini kecuali current bidder (yang baru bid)
            $allBidders = LogBid::where('id_ikan_lelang', $idIkan)
                ->where('id_peserta', '!=', $auth->id_peserta)
                ->select('id_peserta')
                ->distinct()
                ->get();
            
            // Cek siapa saja yang sudah mendapat notifikasi untuk lelang ini
            $notifiedUsers = NotificationLog::where('id_ikan_lelang', $idIkan)->pluck('id_peserta')->toArray();
            
            foreach ($allBidders as $bidder) {
                // Skip jika user sudah pernah dinotifikasi untuk lelang ini
                if (in_array($bidder->id_peserta, $notifiedUsers)) {
                    continue;
                }
                
                // Ambil data member
                $member = Member::find($bidder->id_peserta);
                if (!$member) continue;
                
                // Buat notifikasi
                $notification = Notification::create([
                    'peserta_id' => $member->id_peserta,
                    'label' => 'Koi Auction Alert',
                    'description' => "Hi Mr / Ms. {$member->nama},\n
                    Koi auction yang kamu bid saat ini sudah terlampaui oleh peserta lain. Yuk segera cek dan bid kembali sebelum waktu lelang berakhir!",
                    'link' => route('auction.bid', ['idIkan' => $idIkan]),
                ]);
                
                // Catat bahwa user ini sudah dinotifikasi untuk lelang ini
                NotificationLog::create([
                    'id_peserta' => $member->id_peserta,
                    'id_ikan_lelang' => $idIkan,
                    'notification_id' => $notification->id,
                    'created_at' => Carbon::now()
                ]);
                
                // Kirim notifikasi WhatsApp
                if ($notification) {
                    $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
                    $token = env('QONTAK_API_KEY');

                    $phoneNumber = $member->no_hp;
                    $phoneNumber = preg_replace('/\D/', '', $phoneNumber);
                    if (strpos($phoneNumber, '0') === 0) {
                        $phoneNumber = '62' . substr($phoneNumber, 1);
                    } else if(strpos($phoneNumber, '62') !== 0) {
                        $phoneNumber = '62' . $phoneNumber;
                    }

                    $data = [
                        "to_name" => $member->nama,
                        "to_number" => $phoneNumber,
                        "message_template_id" => "421b85ad-6620-42b8-aafa-77cb8b50d654",
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
                                    "value_text" => $member->nama,
                                    "value" => "customer_name",
                                ]
                            ],
                            "buttons" => []
                        ]
                    ];

                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json',
                    ])->post($url, $data);
                    
                    if ($response->successful()) {
                        $notifData[] = [
                            'user' => $member->nama,
                            'message' => 'Broadcast berhasil dikirim',
                            'data' => $response->json(),
                        ];
                    } else {
                        $notifData[] = [
                            'user' => $member->nama,
                            'message' => 'Broadcast gagal dikirim',
                            'error' => $response->body(),
                            'status' => $response->status(),
                        ];
                    }
                }
            }
        }
        
        $returnData = ['message' => $message];
        if(!empty($notifData)) {
            $returnData['notif'] = $notifData;
        }
        return response()->json($returnData);
    }

    public function detail($idIkan)
    {
        $auth = Auth::guard('member')->user();
        $simple = $this->request->input('simple', null);

        $auctionProduct = EventFish::with(['photo', 'event'])->findOrFail($idIkan);

        if ($simple === 'yes') {
            if ($this->request->ajax()) {

                $maxBidData = LogBidDetail::distinct('nominal_bid')
                    ->whereHas('logBid', function ($q) use ($idIkan) {
                        $q->where('id_ikan_lelang', $idIkan);
                    })
                    ->orderBy('nominal_bid', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->first();

                $addedExtraTime = Carbon::createFromDate($auctionProduct->event->tgl_akhir)
                    ->addMinutes($auctionProduct->extra_time ?? 0)
                    ->toDateTimeString();

                if ($maxBidData !== null) {
                    $addedExtraTime2 = Carbon::createFromDate($maxBidData->created_at)
                        ->addMinutes($auctionProduct->extra_time ?? 0)
                        ->toDateTimeString();

                    if ($addedExtraTime < $addedExtraTime2) {
                        $addedExtraTime = $addedExtraTime2;
                    }
                }

                return response()->json([
                    // 'logBid' => $logBid,
                    // 'autoBid' => $autoBid,
                    // 'maxBid' => $maxBid,
                    // 'idIkan' => $idIkan,
                    // 'meMaxBid' => $meMaxBid,
                    // 'logBids' => $logBids,
                    // 'maxBidData' => $maxBidData,
                    // 'auctionProduct' => $auctionProduct,
                    'addedExtraTime' => $addedExtraTime,
                ]);
            }
        }

        $logBid = null;
        if ($auth) {
            $logBid = LogBid::where('id_peserta', $auth->id_peserta)->where('id_ikan_lelang', $idIkan)->first();
        }

        $logBids = LogBidDetail::with('logBid.member')
            ->distinct('nominal_bid')
            ->whereHas('logBid', function ($q) use ($idIkan) {
                $q->where('id_ikan_lelang', $idIkan);
            })
            ->orderBy('nominal_bid', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit(10)->get();

        foreach ($logBids as $logBid) {
            $logBid->bid_time = Carbon::parse($logBid->created_at)->format('d M H:i:s');
        }

        $maxBidData = $logBids->first();

        $maxBid = $maxBidData->nominal_bid ?? $auctionProduct->ob;

        $autoBid = 0;

        $meMaxBid = false;

        if ($logBid) {
            $nominalBid = $logBid->nominal_bid;
            $maxBid = $nominalBid > $maxBid ? $nominalBid : $maxBid;
            $autoBid = $logBid->auto_bid;

            if (
                $maxBidData->id_bidding === $logBid->id_bidding
                && $maxBidData->id_bidding === $logBid->id_bidding
            ) {
                $meMaxBid = true;
            }
        }

        if ($this->request->ajax()) {

            $addedExtraTime = Carbon::createFromDate($maxBidData->created_at)
                ->addMinutes($auctionProduct->extra_time ?? 0)
                ->toDateTimeString();

            return response()->json([
                'logBid' => $logBid,
                'autoBid' => $autoBid,
                'maxBid' => $maxBid,
                'idIkan' => $idIkan,
                'meMaxBid' => $meMaxBid,
                'logBids' => $logBids,
                'maxBidData' => $maxBidData,
                'auctionProduct' => $auctionProduct,
                'addedExtraTime' => $addedExtraTime,
            ]);
        }

        return view('detail', [
            'auth' => $auth,
            'logBid' => $logBid,
            'autoBid' => $autoBid,
            'maxBid' => $maxBid,
            'idIkan' => $idIkan,
            'meMaxBid' => $meMaxBid,
            'maxBidData' => $maxBidData,
            'auctionProduct' => $auctionProduct,
            'title' => 'ONELITO KOI'
        ]);
    }

    public function bidNow($idIkan)
    {
        return redirect("/auction/$idIkan");
    }
}
