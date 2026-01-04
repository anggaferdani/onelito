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
use App\Services\AuctionTimeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Jobs\ProcessOutbidNotification;

class AuctionController extends Controller
{
    public function index()
    {
        $auth = Auth::guard('member')->user();

        $now = Carbon::now();
        $nowAkhir = Carbon::now()->subDays(2)->endOfDay();

        $currentAuctions = Event::when($auth !== null, function ($q) use ($auth) {
            // ... (logika query with Anda sudah benar)
        }, function ($q) {
            // ... (logika query with Anda sudah benar)
        })
            ->where('tgl_mulai', '<=', $now)
            ->where('tgl_akhir', '>=', $nowAkhir)
            ->where('status_aktif', 1)
            ->where('status_tutup', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        $currentProducts = $currentAuctions
            ->pluck('auctionProducts')
            ->flatten(1)
            ->sortBy('no_ikan', SORT_NATURAL);

        $currentAuction = null;
        $currentTotalBid = 0;
        $currentTotalPrize = 0;
        if (count($currentProducts) > 0) {
            $now = Carbon::now(); // Menggunakan objek Carbon

            foreach ($currentProducts as $product) {
                $currentTotalBid += $product->bid_details_count ?? 0;
                $currentTotalPrize += $product->maxBid->nominal_bid ?? 0;

                // ===== PERUBAHAN DI SINI =====
                // Biarkan variabel ini sebagai objek Carbon, jangan diubah ke string
                $product->tgl_akhir_extra_time = Carbon::createFromDate($product->event->tgl_akhir)
                    ->addMinutes($product->extra_time ?? 0); // HAPUS ->toDateTimeString()

                if ($product->maxBid !== null && $product->maxBid->updated_at >= $product->event->tgl_akhir) {
                    // Buat objek Carbon baru untuk perbandingan
                    $addedExtraTime2 = Carbon::createFromDate($product->maxBid->updated_at)
                        ->addMinutes($product->extra_time ?? 0); // HAPUS ->toDateTimeString()

                    // Objek Carbon bisa dibandingkan secara langsung
                    if ($product->tgl_akhir_extra_time < $addedExtraTime2) {
                        $product->tgl_akhir_extra_time = $addedExtraTime2;
                    }
                }
            }
            // ===================================

            $auctionProducts = $currentProducts->where('tgl_akhir_extra_time', '>', $now);

            $currentAuction = $currentAuctions->first();
        }

        Carbon::setLocale('id');

        return view('auction', [
            'auth' => $auth,
            'currentAuction' => $currentAuction,
            'auctionProducts' => $currentProducts,
            'now' => Carbon::now(),
            'currentTotalPrize' => $currentTotalPrize,
            'currentTotalBid' => $currentTotalBid,
            'auctions' => $currentAuctions,
            'title' => 'auction'
        ]);
    }

    public function getAuctionData()
    {
        $auth = Auth::guard('member')->user();

        $currentAuctions = Event::with([
            'auctionProducts' => function ($q) {
                $q->withCount('bidDetails')->with(['photo', 'maxBid', 'event', 'currency']);
            },
            'auctionProducts.wishlist' => fn ($w) => $w->where('id_peserta', $auth ? $auth->id_peserta : null)
        ])
        ->where('tgl_mulai', '<=', Carbon::now())
        ->where('tgl_akhir', '>=', Carbon::now()->subDays(2)->endOfDay())
        ->where('status_aktif', 1)
        ->where('status_tutup', 0)
        ->orderBy('created_at', 'desc')
        ->get();

        $currentProducts = $currentAuctions
            ->pluck('auctionProducts')
            ->flatten(1)
            ->sortBy('no_ikan', SORT_NATURAL);

        $currentTotalPrize = 0;
        $auctionProductsData = [];

        foreach ($currentProducts as $product) {
            $currentTotalPrize += $product->maxBid?->nominal_bid ?? 0;
            $isHighestBidder = $auth !== null && $product->maxBid !== null && $product->maxBid->id_peserta === $auth->id_peserta;

            $tglAkhirExtraTime = Carbon::createFromDate($product->event->tgl_akhir)
                ->addMinutes($product->extra_time ?? 0);

            if ($product->maxBid !== null && $product->maxBid->updated_at > $product->event->tgl_akhir) {
                $potentialExtraTime = Carbon::createFromDate($product->maxBid->updated_at)
                    ->addMinutes($product->extra_time ?? 0);
                
                if ($potentialExtraTime > $tglAkhirExtraTime) {
                    $tglAkhirExtraTime = $potentialExtraTime;
                }
            }

            $auctionProductsData[] = [
                'id_ikan' => $product->id_ikan,
                'bid_details_count' => $product->bid_details_count,
                'currentMaxBid' => $product->maxBid?->nominal_bid ?? $product->ob,
                'currency' => $product->currency,
                'is_highest_bidder' => $isHighestBidder,
                'tgl_akhir_extra_time' => $tglAkhirExtraTime->toIso8601String(),
            ];
        }

        return response()->json([
            'currentTotalPrize' => $currentTotalPrize,
            'auctionProducts' => $auctionProductsData,
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

        $maxBid = LogBid::where('id_ikan_lelang', $idIkan)
            ->orderBy('nominal_bid', 'desc')
            ->orderBy('waktu_bid', 'asc')
            ->first()->nominal_bid ?? $auctionProduct->ob;

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
            ->orderBy('nominal_bid', 'desc')
            ->orderBy('id_bidding_detail', 'desc')
            ->first();

        Carbon::setLocale('id');

        $addedExtraTime = Carbon::createFromDate($auctionProduct->event->tgl_akhir)
            ->addMinutes($auctionProduct->extra_time ?? 0);

        $now = Carbon::now()->toDateTimeString();

        if ($maxBidData !== null && $maxBidData->logBid->updated_at >= $auctionProduct->event->tgl_akhir) {
            $potentialExtraTime = Carbon::createFromDate($maxBidData->logBid->updated_at)
                ->addMinutes($auctionProduct->extra_time ?? 0);
            
            if ($potentialExtraTime > $addedExtraTime) {
                $addedExtraTime = $potentialExtraTime;
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

    public function detail($idIkan)
    {
        $auth = Auth::guard('member')->user();
        $simple = $this->request->input('simple', null);

        $auctionProduct = EventFish::with(['photo', 'event', 'maxBid'])->findOrFail($idIkan);

        $addedExtraTime = Carbon::createFromDate($auctionProduct->event->tgl_akhir)
            ->addMinutes($auctionProduct->extra_time ?? 0);

        $lastBidDetail = LogBidDetail::whereHas('logBid', function ($q) use ($idIkan) {
            $q->where('id_ikan_lelang', $idIkan);
        })->latest('created_at')->first();

        if ($lastBidDetail && $lastBidDetail->created_at > $auctionProduct->event->tgl_akhir) {
            $potentialExtraTime = Carbon::createFromDate($lastBidDetail->created_at)
                ->addMinutes($auctionProduct->extra_time ?? 0);
            
            if ($potentialExtraTime > $addedExtraTime) {
                $addedExtraTime = $potentialExtraTime;
            }
        }

        if ($simple === 'yes') {
            if ($this->request->ajax()) {
                return response()->json([
                    'addedExtraTime' => $addedExtraTime->toIso8601String(),
                ]);
            }
        }

        $logBid = null;
        if ($auth) {
            $logBid = LogBid::where('id_peserta', $auth->id_peserta)->where('id_ikan_lelang', $idIkan)->first();
        }

        $logBids = LogBidDetail::with('logBid.member')
            ->whereHas('logBid', function ($q) use ($idIkan) {
                $q->where('id_ikan_lelang', $idIkan);
            })
            ->orderBy('nominal_bid', 'desc')
            ->orderBy('id_bidding_detail', 'desc')
            ->limit(10)->get();

        foreach ($logBids as $logBidItem) {
            $logBidItem->bid_time = Carbon::parse($logBidItem->created_at)
                                        ->setTimezone('Asia/Jakarta')
                                        ->format('d M H:i:s');
        }

        $maxBidData = $logBids->first();
        $maxBid = $maxBidData->nominal_bid ?? $auctionProduct->ob;
        $autoBid = $logBid->auto_bid ?? 0;
        
        $meMaxBid = false;
        if ($auth && $maxBidData && $maxBidData->logBid && $maxBidData->logBid->id_peserta === $auth->id_peserta) {
            $meMaxBid = true;
        }

        if ($this->request->ajax()) {
            return response()->json([
                'logBid' => $logBid,
                'autoBid' => $autoBid,
                'maxBid' => $maxBid,
                'idIkan' => $idIkan,
                'meMaxBid' => $meMaxBid,
                'logBids' => $logBids,
                'maxBidData' => $maxBidData,
                'auctionProduct' => $auctionProduct,
                'addedExtraTime' => $addedExtraTime->toIso8601String(),
            ]);
        }

        return response()->json([
            'logBid'        => $logBid,
            'myAutoBid'     => $autoBid,
            'autoBid'       => $autoBid,
            'maxBid'        => $maxBid,
            'idIkan'        => $idIkan,
            'meMaxBid'      => $meMaxBid,
            'logBids'       => $logBids,
            'maxBidData'    => $maxBidData,
            'auctionProduct'=> $auctionProduct,
            'addedExtraTime'=> $addedExtraTime->toIso8601String(),
        ]);
    }

    public function bidProcess($idIkan)
    {
        $auth = Auth::guard('member')->user();
        if (!$auth) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return DB::transaction(function () use ($idIkan, $auth) {

            $auctionProduct = EventFish::lockForUpdate()->findOrFail($idIkan);

            if (AuctionTimeService::isFishEnded($auctionProduct)) {
                return response()->json([
                    'message' => 'Auction ikan sudah berakhir'
                ], 400);
            }

            $nominalBid = request()->input('nominal_bid');
            $autoBid    = request()->input('auto_bid');

            $currentHighest = LogBid::where('id_ikan_lelang', $idIkan)
                ->orderBy('nominal_bid', 'desc')
                ->orderBy('waktu_bid', 'asc')
                ->lockForUpdate()
                ->first();

            $currentPrice = $currentHighest->nominal_bid ?? $auctionProduct->ob;

            if ($autoBid === 0 || $autoBid === '0') {
                $logBid = LogBid::where('id_peserta', $auth->id_peserta)
                    ->where('id_ikan_lelang', $idIkan)
                    ->first();

                if ($logBid) {
                    $logBid->auto_bid = null;
                    $logBid->save();
                }

                return response()->json(['message' => 'Auto bid cancelled']);
            }

            if ($autoBid !== null) {
                if ($autoBid % $auctionProduct->kb !== 0) {
                    return response()->json([
                        'message' => 'Nominal auto bid harus sesuai kelipatan'
                    ], 400);
                }

                if ($autoBid <= $currentPrice) {
                    return response()->json([
                        'message' => 'Auto bid harus lebih besar dari harga saat ini'
                    ], 400);
                }
            }

            if ($nominalBid !== null) {
                if ($nominalBid <= $currentPrice) {
                    return response()->json([
                        'message' => 'Nominal bid harus lebih tinggi dari harga saat ini'
                    ], 400);
                }

                if (($nominalBid - $currentPrice) % $auctionProduct->kb !== 0) {
                    return response()->json([
                        'message' => 'Nominal bid harus sesuai kelipatan bid'
                    ], 400);
                }
            }

            $logBid = LogBid::firstOrCreate(
                [
                    'id_peserta'     => $auth->id_peserta,
                    'id_ikan_lelang' => $idIkan
                ],
                [
                    'nominal_bid' => $auctionProduct->ob,
                    'waktu_bid'   => now(),
                    'status_aktif'=> 1
                ]
            );

            $isNewBidder = $logBid->wasRecentlyCreated;
            $isCurrentWinner = $currentHighest && $currentHighest->id_peserta == $auth->id_peserta;

            if ($nominalBid !== null) {
                $logBid->nominal_bid = $nominalBid;
                $logBid->waktu_bid   = now();
                $logBid->save();

                LogBidDetail::create([
                    'id_bidding'  => $logBid->id_bidding,
                    'nominal_bid' => $nominalBid,
                    'status_aktif'=> 1,
                    'status_bid'  => 0,
                ]);

                $currentPrice = $nominalBid;
            }

            if ($autoBid !== null) {
                $oldAutoBid = $logBid->auto_bid;
                
                $logBid->auto_bid = $autoBid;
                $logBid->save();

                if ($isCurrentWinner && !$isNewBidder && $nominalBid === null && $oldAutoBid !== null) {
                    $hasCompetitorHigher = LogBid::where('id_ikan_lelang', $idIkan)
                        ->where('id_peserta', '!=', $auth->id_peserta)
                        ->whereNotNull('auto_bid')
                        ->where('auto_bid', '>=', $autoBid)
                        ->exists();
                    
                    if (!$hasCompetitorHigher) {
                        return response()->json(['message' => 'Auto bid limit updated']);
                    }
                }

                if ($autoBid > $currentPrice) {
                    $highestCompetitor = LogBid::where('id_ikan_lelang', $idIkan)
                        ->where('id_peserta', '!=', $auth->id_peserta)
                        ->whereNotNull('auto_bid')
                        ->orderBy('auto_bid', 'desc')
                        ->orderBy('waktu_bid', 'asc')
                        ->first();

                    $highestCompetitorAutoBid = $highestCompetitor ? $highestCompetitor->auto_bid : null;

                    $userNewBid = null;
                    
                    if ($highestCompetitorAutoBid && $highestCompetitorAutoBid >= $currentPrice) {
                        
                        if ($autoBid > $highestCompetitorAutoBid) {
                            $userNewBid = $highestCompetitorAutoBid + $auctionProduct->kb;
                            
                            if ($userNewBid > $autoBid) {
                                $userNewBid = $autoBid;
                            }
                        } elseif ($autoBid == $highestCompetitorAutoBid) {
                            $userNewBid = $autoBid;
                        } else {
                            $userNewBid = $autoBid;
                        }
                    } else {
                        $userNewBid = $currentPrice + $auctionProduct->kb;
                        
                        if ($userNewBid > $autoBid) {
                            $userNewBid = $autoBid;
                        }
                    }

                    if ($userNewBid !== null) {
                        $logBid->nominal_bid = $userNewBid;
                        $logBid->waktu_bid   = now();
                        $logBid->save();

                        LogBidDetail::create([
                            'id_bidding'  => $logBid->id_bidding,
                            'nominal_bid' => $userNewBid,
                            'status_aktif'=> 1,
                            'status_bid'  => 1,
                        ]);

                        $currentPrice = $userNewBid;
                    }
                }
            }

            $shouldTriggerEngine = false;
            
            if ($nominalBid !== null) {
                $shouldTriggerEngine = true;
            } elseif ($autoBid !== null) {
                $shouldTriggerEngine = ($autoBid > ($currentHighest->nominal_bid ?? $auctionProduct->ob));
            }
            
            if ($shouldTriggerEngine) {
                $this->processAutoBidEngine($idIkan, $auctionProduct, $currentPrice, $auth->id_peserta);
            }

            AuctionTimeService::extendExtraTime($auctionProduct);

            if (AuctionTimeService::isOutbidSession($auctionProduct)) {
                \App\Jobs\ProcessOutbidNotification::dispatch(
                    $idIkan,
                    $auth->id_peserta
                )->onQueue('auction-notification');
            }

            return response()->json(['message' => 'success']);
        });
    }

    private function processAutoBidEngine($idIkan, $auctionProduct, $currentPrice, $triggeredBy)
    {
        $kb = $auctionProduct->kb;
        $maxIterations = 100;
        $iteration = 0;

        while ($iteration < $maxIterations) {
            $iteration++;

            $autoBidders = LogBid::where('id_ikan_lelang', $idIkan)
                ->where('id_peserta', '!=', $triggeredBy)
                ->whereNotNull('auto_bid')
                ->where('auto_bid', '>=', $currentPrice)
                ->orderBy('auto_bid', 'desc')
                ->orderBy('waktu_bid', 'asc')
                ->lockForUpdate()
                ->get();

            if ($autoBidders->count() === 0) {
                break;
            }

            $winner = $autoBidders[0];
            $challenger = $autoBidders->count() > 1 ? $autoBidders[1] : null;

            $newPrice = $this->calculateNewPrice(
                $currentPrice,
                $winner,
                $challenger,
                $kb
            );

            if ($newPrice === null || $newPrice < $currentPrice) {
                break;
            }

            if ($newPrice > $winner->auto_bid) {
                $newPrice = $winner->auto_bid;
            }

            $winner->nominal_bid = $newPrice;
            $winner->waktu_bid   = now();
            $winner->save();

            LogBidDetail::create([
                'id_bidding'  => $winner->id_bidding,
                'nominal_bid' => $newPrice,
                'status_aktif'=> 1,
                'status_bid'  => 1,
            ]);

            $currentPrice = $newPrice;
            $triggeredBy = $winner->id_peserta;

            if ($newPrice >= $winner->auto_bid) {
                break;
            }

            if (!$challenger) {
                break;
            }
        }
    }

    private function calculateNewPrice($currentPrice, $winner, $challenger, $kb)
    {
        if ($challenger) {
            if ($winner->auto_bid == $challenger->auto_bid) {
                return $winner->auto_bid;
            }
            
            $targetPrice = $challenger->auto_bid + $kb;
            return min($winner->auto_bid, $targetPrice);
            
        } else {
            if ($winner->auto_bid == $currentPrice) {
                return $currentPrice;
            }
            
            $targetPrice = $currentPrice + $kb;
            return min($winner->auto_bid, $targetPrice);
        }
    }

    public function bidNow($idIkan)
    {
        return redirect("/auction/$idIkan");
    }
}
