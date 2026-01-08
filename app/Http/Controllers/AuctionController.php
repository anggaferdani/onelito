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
use Illuminate\Support\Facades\Log;
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
        $auth = Auth::guard('member')->user();

        $auctionProduct = EventFish::with([
            'photo',
            'event',
            'maxBid' => function($q) {
                $q->orderBy('nominal_bid', 'desc')
                ->orderBy('waktu_bid', 'asc');
            }
        ])->findOrFail($idIkan);

        $logBid = null;
        if ($auth) {
            $logBid = LogBid::where('id_peserta', $auth->id_peserta)
                ->where('id_ikan_lelang', $idIkan)
                ->first();
        }

        $maxBidData = $auctionProduct->maxBid;
        $maxBid = $maxBidData?->nominal_bid ?? $auctionProduct->ob;
        
        $autoBid = $logBid?->auto_bid ?? 0;
        
        // ✅ BARU: Check apakah manual bid harus disabled
        $disableManualBid = $this->shouldDisableManualBid($auth, $logBid, $maxBid);
        
        Carbon::setLocale('id');
        $addedExtraTime = Carbon::parse($auctionProduct->event->tgl_akhir)
            ->addMinutes($auctionProduct->extra_time ?? 0);

        if ($maxBidData && 
            $maxBidData->updated_at >= $auctionProduct->event->tgl_akhir) {
            
            $potentialExtraTime = Carbon::parse($maxBidData->updated_at)
                ->addMinutes($auctionProduct->extra_time ?? 0);
            
            if ($potentialExtraTime > $addedExtraTime) {
                $addedExtraTime = $potentialExtraTime;
            }
        }

        return view('bid', [
            'auth' => $auth,
            'logBid' => $logBid,
            'autoBid' => (int) $autoBid,
            'maxBid' => (int) $maxBid,
            'idIkan' => $idIkan,
            'now' => now()->toDateTimeString(),
            'auctionProduct' => $auctionProduct,
            'title' => 'auction',
            'addedExtraTime' => $addedExtraTime,
            'maxBidData' => $maxBidData,
            'currentPrice' => $maxBid,
            'disableManualBid' => $disableManualBid, // ✅ BARU
        ]);
    }

    // ========================================
    // METHOD 2: bidOptimized() - FASTEST (Query Builder)
    // ========================================
    public function bidOptimized($idIkan)
    {
        $auth = Auth::guard('member')->user();

        // ✅ Single optimized query with JOIN
        $data = DB::table('m_ikan_lelang as mik')
            ->leftJoin('m_event as me', 'mik.id_event', '=', 'me.id_event')
            ->leftJoin('t_log_bidding as tlb', function($join) use ($idIkan) {
                $join->on('tlb.id_ikan_lelang', '=', 'mik.id_ikan')
                     ->where('tlb.status_aktif', 1)
                     ->whereRaw('tlb.nominal_bid = (
                         SELECT MAX(nominal_bid) 
                         FROM t_log_bidding 
                         WHERE id_ikan_lelang = ? 
                         AND status_aktif = 1
                     )', [$idIkan])
                     ->whereRaw('tlb.waktu_bid = (
                         SELECT MIN(waktu_bid)
                         FROM t_log_bidding
                         WHERE id_ikan_lelang = ?
                         AND status_aktif = 1
                         AND nominal_bid = (
                             SELECT MAX(nominal_bid)
                             FROM t_log_bidding
                             WHERE id_ikan_lelang = ?
                             AND status_aktif = 1
                         )
                     )', [$idIkan, $idIkan]);
            })
            ->where('mik.id_ikan', $idIkan)
            ->select([
                'mik.*',
                'me.tgl_akhir',
                'me.status_tutup',
                'me.nama_event',
                'tlb.id_bidding as max_bid_id',
                'tlb.nominal_bid as max_bid',
                'tlb.waktu_bid as max_bid_time',
                'tlb.updated_at as max_bid_updated',
                'tlb.id_peserta as max_bid_peserta'
            ])
            ->first();

        if (!$data) {
            abort(404);
        }

        // Get user's bid jika authenticated
        $logBid = null;
        if ($auth) {
            $logBid = LogBid::where('id_peserta', $auth->id_peserta)
                ->where('id_ikan_lelang', $idIkan)
                ->first();
        }

        // Prepare data
        $maxBid = $data->max_bid ?? $data->ob;
        $autoBid = $logBid?->auto_bid ?? 0;

        // Calculate extra time
        Carbon::setLocale('id');
        $addedExtraTime = Carbon::parse($data->tgl_akhir)
            ->addMinutes($data->extra_time ?? 0);

        if ($data->max_bid_updated && 
            Carbon::parse($data->max_bid_updated) >= Carbon::parse($data->tgl_akhir)) {
            
            $potentialExtraTime = Carbon::parse($data->max_bid_updated)
                ->addMinutes($data->extra_time ?? 0);
            
            if ($potentialExtraTime > $addedExtraTime) {
                $addedExtraTime = $potentialExtraTime;
            }
        }

        return view('bid', [
            'auth' => $auth,
            'logBid' => $logBid,
            'autoBid' => (int) $autoBid,
            'maxBid' => (int) $maxBid,
            'idIkan' => $idIkan,
            'now' => now()->toDateTimeString(),
            'auctionProduct' => $data,
            'title' => 'auction',
            'addedExtraTime' => $addedExtraTime,
            'maxBidData' => $data->max_bid_id ? (object)[
                'id_bidding' => $data->max_bid_id,
                'nominal_bid' => $data->max_bid,
                'waktu_bid' => $data->max_bid_time,
                'updated_at' => $data->max_bid_updated
            ] : null,
            'currentPrice' => $maxBid,
        ]);
    }

    // ========================================
    // METHOD 3: detail() - FIXED VERSION
    // ========================================
    public function detail($idIkan)
    {
        $auth = Auth::guard('member')->user();
        $simple = $this->request->input('simple', null);

        $auctionProduct = EventFish::with(['photo', 'event', 'maxBid'])
            ->findOrFail($idIkan);

        $logBids = $auctionProduct->bidDetails()
            ->with('logBid.member')
            ->orderBy('t_log_bidding_detail.nominal_bid', 'desc')
            ->orderBy('t_log_bidding_detail.id_bidding_detail', 'desc')
            ->limit(10)
            ->get();

        $lastBidDetail = $logBids->first();
        
        Carbon::setLocale('id');
        $addedExtraTime = Carbon::parse($auctionProduct->event->tgl_akhir)
            ->addMinutes($auctionProduct->extra_time ?? 0);

        if ($lastBidDetail && 
            $lastBidDetail->created_at > $auctionProduct->event->tgl_akhir) {
            
            $potentialExtraTime = Carbon::parse($lastBidDetail->created_at)
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
            $logBid = LogBid::where('id_peserta', $auth->id_peserta)
                ->where('id_ikan_lelang', $idIkan)
                ->first();
        }

        $logBids->each(function($item) {
            $item->bid_time = Carbon::parse($item->created_at)
                ->setTimezone('Asia/Jakarta')
                ->format('d M H:i:s');
        });

        $maxBidData = $logBids->first();
        $maxBid = $maxBidData?->nominal_bid ?? $auctionProduct->ob;
        $autoBid = $logBid?->auto_bid ?? 0;
        
        $meMaxBid = $auth && $maxBidData && 
                    $maxBidData->logBid?->id_peserta === $auth->id_peserta;

        // ✅ BARU: Check status disable manual bid
        $disableManualBid = $this->shouldDisableManualBid($auth, $logBid, $maxBid);

        return response()->json([
            'logBid' => $logBid,
            'autoBid' => $autoBid,
            'myAutoBid' => $autoBid,
            'maxBid' => $maxBid,
            'idIkan' => $idIkan,
            'meMaxBid' => $meMaxBid,
            'logBids' => $logBids,
            'maxBidData' => $maxBidData,
            'auctionProduct' => $auctionProduct,
            'addedExtraTime' => $addedExtraTime->toIso8601String(),
            'disableManualBid' => $disableManualBid, // ✅ BARU
        ]);
    }

    // ========================================
    // METHOD 4: detailOptimized() - FASTEST (Query Builder)
    // ========================================
    public function detailOptimized($idIkan)
    {
        $auth = Auth::guard('member')->user();
        $simple = $this->request->input('simple', null);

        // Get fish with event
        $auctionProduct = EventFish::with(['photo', 'event', 'maxBid'])
            ->findOrFail($idIkan);

        // ✅ Optimized query dengan JOIN (bukan whereHas)
        $logBids = DB::table('t_log_bidding_detail as lbd')
            ->join('t_log_bidding as lb', 'lbd.id_bidding', '=', 'lb.id_bidding')
            ->leftJoin('m_peserta as mp', 'lb.id_peserta', '=', 'mp.id_peserta')
            ->where('lb.id_ikan_lelang', $idIkan)
            ->where('lb.status_aktif', 1)
            ->where('lbd.status_aktif', 1)
            ->select([
                'lbd.*',
                'lb.id_bidding',
                'lb.id_peserta',
                'lb.id_ikan_lelang',
                'lb.nominal_bid as log_nominal_bid',
                'lb.waktu_bid',
                'lb.auto_bid',
                'mp.nama as member_name',
                'mp.username as member_username',
                'mp.email as member_email',
            ])
            ->orderBy('lbd.nominal_bid', 'desc')
            ->orderBy('lbd.id_bidding_detail', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                // Format timestamp
                $item->bid_time = Carbon::parse($item->created_at)
                    ->setTimezone('Asia/Jakarta')
                    ->format('d M H:i:s');
                
                // Structure like Eloquent relationship
                $item->logBid = (object)[
                    'id_bidding' => $item->id_bidding,
                    'id_peserta' => $item->id_peserta,
                    'id_ikan_lelang' => $item->id_ikan_lelang,
                    'nominal_bid' => $item->log_nominal_bid,
                    'waktu_bid' => $item->waktu_bid,
                    'auto_bid' => $item->auto_bid,
                    'member' => (object)[
                        'id_peserta' => $item->id_peserta,
                        'nama' => $item->member_name,
                        'username' => $item->member_username,
                        'email' => $item->member_email,
                    ]
                ];
                
                return $item;
            });

        $lastBidDetail = $logBids->first();
        
        // Calculate extra time
        Carbon::setLocale('id');
        $addedExtraTime = Carbon::parse($auctionProduct->event->tgl_akhir)
            ->addMinutes($auctionProduct->extra_time ?? 0);

        if ($lastBidDetail && 
            Carbon::parse($lastBidDetail->created_at) > Carbon::parse($auctionProduct->event->tgl_akhir)) {
            
            $potentialExtraTime = Carbon::parse($lastBidDetail->created_at)
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
            $logBid = LogBid::where('id_peserta', $auth->id_peserta)
                ->where('id_ikan_lelang', $idIkan)
                ->first();
        }

        $maxBidData = $logBids->first();
        $maxBid = $maxBidData?->nominal_bid ?? $auctionProduct->ob;
        $autoBid = $logBid?->auto_bid ?? 0;
        
        $meMaxBid = $auth && $maxBidData && 
                    $maxBidData->id_peserta === $auth->id_peserta;

        return response()->json([
            'logBid' => $logBid,
            'autoBid' => $autoBid,
            'myAutoBid' => $autoBid,
            'maxBid' => $maxBid,
            'idIkan' => $idIkan,
            'meMaxBid' => $meMaxBid,
            'logBids' => $logBids,
            'maxBidData' => $maxBidData,
            'auctionProduct' => $auctionProduct,
            'addedExtraTime' => $addedExtraTime->toIso8601String(),
        ]);
    }

    // ========================================
    // 3. OPTIMIZED bidProcess() WITH BETTER LOCKING
    // ========================================
    public function bidProcess($idIkan)
    {
        $auth = Auth::guard('member')->user();
        if (!$auth) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return DB::transaction(function () use ($idIkan, $auth) {
            
            $auctionProduct = EventFish::lockForUpdate()->findOrFail($idIkan);

            if (AuctionTimeService::isFishEnded($auctionProduct)) {
                return response()->json(['message' => 'Auction ikan sudah berakhir'], 400);
            }

            $nominalBid = request()->input('nominal_bid');
            $autoBid = request()->input('auto_bid');

            $currentHighest = LogBid::where('id_ikan_lelang', $idIkan)
                ->lockForUpdate()
                ->orderBy('nominal_bid', 'desc')
                ->orderBy('waktu_bid', 'asc')
                ->first();

            $currentPrice = $currentHighest?->nominal_bid ?? $auctionProduct->ob;

            if ($autoBid === 0 || $autoBid === '0') {
                return $this->cancelAutoBid($auth->id_peserta, $idIkan);
            }

            $validation = $this->validateBid(
                $nominalBid, 
                $autoBid, 
                $currentPrice, 
                $auctionProduct->kb
            );
            
            if ($validation !== true) {
                return $validation;
            }

            $logBid = LogBid::lockForUpdate()
                ->firstOrCreate(
                    [
                        'id_peserta' => $auth->id_peserta,
                        'id_ikan_lelang' => $idIkan
                    ],
                    [
                        'nominal_bid' => $auctionProduct->ob,
                        'waktu_bid' => now(),
                        'status_aktif' => 1
                    ]
                );

            $isNewBidder = $logBid->wasRecentlyCreated;
            $isCurrentWinner = $currentHighest && 
                            $currentHighest->id_peserta == $auth->id_peserta;

            // Process manual bid
            if ($nominalBid !== null) {
                $currentPrice = $this->processManualBidWithTie(
                    $logBid, 
                    $nominalBid,
                    $idIkan,
                    $auth->id_peserta,
                    $auctionProduct->kb
                );
            }

            // Process auto bid
            if ($autoBid !== null) {
                $result = $this->processAutoBid(
                    $logBid,
                    $autoBid,
                    $currentPrice,
                    $auctionProduct->kb,
                    $idIkan,
                    $isCurrentWinner,
                    $isNewBidder,
                    $nominalBid,
                    $auth->id_peserta
                );
                
                if ($result['skip_engine']) {
                    return response()->json(['message' => $result['message']]);
                }
                
                $currentPrice = $result['currentPrice'];
            }

            // Trigger auto bid engine
            $shouldTriggerEngine = ($nominalBid !== null) || 
                ($autoBid !== null && $autoBid > ($currentHighest?->nominal_bid ?? $auctionProduct->ob));
            
            if ($shouldTriggerEngine) {
                $this->processAutoBidEngine(
                    $idIkan, 
                    $auctionProduct, 
                    $currentPrice, 
                    $auth->id_peserta
                );
            }

            AuctionTimeService::extendExtraTime($auctionProduct);

            if (AuctionTimeService::isOutbidSession($auctionProduct)) {
                dispatch(new \App\Jobs\ProcessOutbidNotification(
                    $idIkan,
                    $auth->id_peserta
                ))->onQueue('auction-notification');
            }

            return response()->json(['message' => 'success']);
        });
    }

    // ========================================
    // 4. OPTIMIZED AUTO BID ENGINE
    // ========================================
    private function processAutoBidEngine($idIkan, $auctionProduct, $currentPrice, $triggeredBy)
    {
        $kb = $auctionProduct->kb;
        $maxIterations = 50;
        $iteration = 0;
        
        $processedBidders = [];

        while ($iteration < $maxIterations) {
            $iteration++;

            // Get potential bidders
            $autoBidders = LogBid::where('id_ikan_lelang', $idIkan)
                ->where('id_peserta', '!=', $triggeredBy)
                ->whereNotNull('auto_bid')
                ->where('auto_bid', '>=', $currentPrice)
                ->whereNotIn('id_peserta', $processedBidders)
                ->orderBy('auto_bid', 'desc')
                ->orderBy('waktu_bid', 'asc')
                ->lockForUpdate()
                ->limit(2)
                ->get();

            if ($autoBidders->isEmpty()) {
                break;
            }

            $winner = $autoBidders->first();
            $challenger = $autoBidders->count() > 1 ? $autoBidders->get(1) : null;

            $priceResult = $this->calculateNewPriceWithTie(
                $currentPrice,
                $winner,
                $challenger,
                $kb
            );

            if ($priceResult['newPrice'] === null || $priceResult['newPrice'] <= $currentPrice) {
                break;
            }

            $newPrice = $priceResult['newPrice'];
            $isTie = $priceResult['isTie'];

            // Update winner
            $winner->update([
                'nominal_bid' => $newPrice,
                'waktu_bid' => now()
            ]);

            LogBidDetail::create([
                'id_bidding' => $winner->id_bidding,
                'nominal_bid' => $newPrice,
                'status_aktif' => 1,
                'status_bid' => 1,
            ]);

            // Update challenger jika tie
            if ($isTie && $challenger) {
                $challenger->update([
                    'nominal_bid' => $newPrice,
                    'waktu_bid' => now()
                ]);

                LogBidDetail::create([
                    'id_bidding' => $challenger->id_bidding,
                    'nominal_bid' => $newPrice,
                    'status_aktif' => 1,
                    'status_bid' => 1,
                ]);
            }

            $currentPrice = $newPrice;
            $triggeredBy = $winner->id_peserta;
            $processedBidders[] = $winner->id_peserta;
            
            if ($isTie && $challenger) {
                $processedBidders[] = $challenger->id_peserta;
            }

            if ($newPrice >= $winner->auto_bid || !$challenger) {
                break;
            }

            if ($iteration > 10 && ($iteration % 5) === 0) {
                usleep(100000);
            }
        }

        if ($iteration >= $maxIterations) {
            Log::warning("Auto bid engine reached max iterations", [
                'fish_id' => $idIkan,
                'iterations' => $iteration,
                'final_price' => $currentPrice
            ]);
        }
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    private function calculateFinalEndTime($auctionProduct, $lastBidData)
    {
        Carbon::setLocale('id');
        
        $addedExtraTime = Carbon::parse($auctionProduct->event->tgl_akhir)
            ->addMinutes($auctionProduct->extra_time ?? 0);

        if ($lastBidData && 
            $lastBidData->updated_at >= $auctionProduct->event->tgl_akhir) {
            
            $potentialExtraTime = Carbon::parse($lastBidData->updated_at)
                ->addMinutes($auctionProduct->extra_time ?? 0);
            
            if ($potentialExtraTime > $addedExtraTime) {
                $addedExtraTime = $potentialExtraTime;
            }
        }

        return $addedExtraTime;
    }

    private function cancelAutoBid($idPeserta, $idIkan)
    {
        $logBid = LogBid::where('id_peserta', $idPeserta)
            ->where('id_ikan_lelang', $idIkan)
            ->first();

        if ($logBid) {
            $logBid->update(['auto_bid' => null]);
        }

        return response()->json(['message' => 'Auto bid cancelled']);
    }

    private function validateBid($nominalBid, $autoBid, $currentPrice, $kb)
    {
        if ($autoBid !== null) {
            if ($autoBid % $kb !== 0) {
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

            if (($nominalBid - $currentPrice) % $kb !== 0) {
                return response()->json([
                    'message' => 'Nominal bid harus sesuai kelipatan bid'
                ], 400);
            }
        }

        return true;
    }

    private function processManualBid($logBid, $nominalBid)
    {
        $logBid->update([
            'nominal_bid' => $nominalBid,
            'waktu_bid' => now()
        ]);

        LogBidDetail::create([
            'id_bidding' => $logBid->id_bidding,
            'nominal_bid' => $nominalBid,
            'status_aktif' => 1,
            'status_bid' => 0,
        ]);

        return $nominalBid;
    }

    private function processAutoBid(
        $logBid, 
        $autoBid, 
        $currentPrice, 
        $kb, 
        $idIkan, 
        $isCurrentWinner, 
        $isNewBidder, 
        $nominalBid, 
        $authId
    ) {
        $oldAutoBid = $logBid->auto_bid;
        $logBid->update(['auto_bid' => $autoBid]);

        // ✅ FIXED: Cek apakah user adalah top bidder dengan nominal = currentPrice
        // (Handle case: cancel auto bid → set auto bid lagi)
        $isCurrentTopBidder = ($logBid->nominal_bid == $currentPrice);

        // Skip engine jika update winner's limit tanpa kompetitor
        if ($isCurrentWinner && !$isNewBidder && 
            $nominalBid === null && $oldAutoBid !== null) {
            
            $hasCompetitorHigher = LogBid::where('id_ikan_lelang', $idIkan)
                ->where('id_peserta', '!=', $authId)
                ->whereNotNull('auto_bid')
                ->where('auto_bid', '>=', $autoBid)
                ->exists();
            
            if (!$hasCompetitorHigher) {
                return [
                    'skip_engine' => true,
                    'message' => 'Auto bid limit updated',
                    'currentPrice' => $currentPrice
                ];
            }
        }

        // Calculate immediate auto bid
        if ($autoBid > $currentPrice) {
            $highestCompetitor = LogBid::where('id_ikan_lelang', $idIkan)
                ->where('id_peserta', '!=', $authId)
                ->whereNotNull('auto_bid')
                ->orderBy('auto_bid', 'desc')
                ->orderBy('waktu_bid', 'asc')
                ->first();

            $result = $this->calculateImmediateAutoBidWithTie(
                $currentPrice,
                $autoBid,
                $highestCompetitor?->auto_bid,
                $kb,
                $idIkan,
                $logBid,
                $isCurrentTopBidder  // ✅ Pass parameter baru
            );

            // ✅ Jika newBid = null, berarti user sudah top bidder, skip update
            if ($result['newBid'] !== null) {
                $logBid->update([
                    'nominal_bid' => $result['newBid'],
                    'waktu_bid' => now()
                ]);

                LogBidDetail::create([
                    'id_bidding' => $logBid->id_bidding,
                    'nominal_bid' => $result['newBid'],
                    'status_aktif' => 1,
                    'status_bid' => 1,
                ]);

                // Update tied bidders
                foreach ($result['tiedBidders'] as $tiedBidder) {
                    $tiedBidder->update([
                        'nominal_bid' => $result['newBid'],
                        'waktu_bid' => now()
                    ]);

                    LogBidDetail::create([
                        'id_bidding' => $tiedBidder->id_bidding,
                        'nominal_bid' => $result['newBid'],
                        'status_aktif' => 1,
                        'status_bid' => 1,
                    ]);
                }

                $currentPrice = $result['newBid'];
            }
        }

        return [
            'skip_engine' => false,
            'currentPrice' => $currentPrice
        ];
    }

    private function calculateImmediateAutoBid(
        $currentPrice, 
        $autoBid, 
        $competitorAutoBid, 
        $kb
    ) {
        if ($competitorAutoBid && $competitorAutoBid >= $currentPrice) {
            if ($autoBid > $competitorAutoBid) {
                return min($competitorAutoBid + $kb, $autoBid);
            }
            return $autoBid;
        }
        
        return min($currentPrice + $kb, $autoBid);
    }

    private function calculateNewPrice($currentPrice, $winner, $challenger, $kb)
    {
        if ($challenger) {
            if ($winner->auto_bid == $challenger->auto_bid) {
                return $winner->auto_bid;
            }
            return min($winner->auto_bid, $challenger->auto_bid + $kb);
        }
        
        if ($winner->auto_bid == $currentPrice) {
            return $currentPrice;
        }
        
        return min($winner->auto_bid, $currentPrice + $kb);
    }

    private function calculateNewPriceWithTie($currentPrice, $winner, $challenger, $kb)
    {
        if ($challenger) {
            // CASE 1: TIE - Auto bid sama
            if ($winner->auto_bid == $challenger->auto_bid) {
                return [
                    'newPrice' => $winner->auto_bid,
                    'isTie' => true
                ];
            }
            
            // CASE 2: Winner lebih tinggi
            $newPrice = min($winner->auto_bid, $challenger->auto_bid + $kb);
            $isTie = ($newPrice == $challenger->auto_bid);
            
            return [
                'newPrice' => $newPrice,
                'isTie' => $isTie
            ];
        }
        
        // CASE 3: No challenger
        if ($winner->auto_bid == $currentPrice) {
            return [
                'newPrice' => $currentPrice,
                'isTie' => false
            ];
        }
        
        return [
            'newPrice' => min($winner->auto_bid, $currentPrice + $kb),
            'isTie' => false
        ];
    }

    // ========================================
    // PROCESS MANUAL BID WITH TIE HANDLING
    // ========================================
    private function processManualBidWithTie($logBid, $nominalBid, $idIkan, $authId, $kb)
    {
        // Update manual bidder
        $logBid->update([
            'nominal_bid' => $nominalBid,
            'waktu_bid' => now()
        ]);

        LogBidDetail::create([
            'id_bidding' => $logBid->id_bidding,
            'nominal_bid' => $nominalBid,
            'status_aktif' => 1,
            'status_bid' => 0,
        ]);

        // Check if manual bid matches auto bid (TIE)
        $tiedAutoBidders = LogBid::where('id_ikan_lelang', $idIkan)
            ->where('id_peserta', '!=', $authId)
            ->whereNotNull('auto_bid')
            ->where('auto_bid', $nominalBid)
            ->lockForUpdate()
            ->get();

        foreach ($tiedAutoBidders as $tiedBidder) {
            $tiedBidder->update([
                'nominal_bid' => $nominalBid,
                'waktu_bid' => now()
            ]);

            LogBidDetail::create([
                'id_bidding' => $tiedBidder->id_bidding,
                'nominal_bid' => $nominalBid,
                'status_aktif' => 1,
                'status_bid' => 1,
            ]);
        }

        return $nominalBid;
    }

    // ========================================
    // UPDATED: Calculate Immediate Auto Bid with Tie
    // ========================================
    private function calculateImmediateAutoBidWithTie(
        $currentPrice, 
        $autoBid, 
        $competitorAutoBid, 
        $kb,
        $idIkan,
        $logBid,
        $isCurrentTopBidder  // ✅ NEW PARAMETER
    ) {
        // ✅ CRITICAL FIX: Jika user sudah jadi top bidder dengan nominal = currentPrice
        // Jangan naikkan lagi, hanya update auto_bid limit
        if ($isCurrentTopBidder && $logBid->nominal_bid == $currentPrice) {
            // Check apakah ada competitor yang bisa mengalahkan
            $hasStrongerCompetitor = LogBid::where('id_ikan_lelang', $idIkan)
                ->where('id_peserta', '!=', $logBid->id_peserta)
                ->whereNotNull('auto_bid')
                ->where('auto_bid', '>', $currentPrice)
                ->exists();
            
            // Jika tidak ada competitor lebih kuat, jangan naikkan
            if (!$hasStrongerCompetitor) {
                return [
                    'newBid' => null,  // ✅ No bid increase
                    'tiedBidders' => []
                ];
            }
        }

        // No competitor atau competitor lebih rendah
        if (!$competitorAutoBid || $competitorAutoBid < $currentPrice) {
            return [
                'newBid' => min($currentPrice + $kb, $autoBid),
                'tiedBidders' => []
            ];
        }

        // TIE: Auto bid sama
        if ($autoBid == $competitorAutoBid) {
            $tiedBidders = LogBid::where('id_ikan_lelang', $idIkan)
                ->where('id_peserta', '!=', $logBid->id_peserta)
                ->whereNotNull('auto_bid')
                ->where('auto_bid', $autoBid)
                ->lockForUpdate()
                ->get();

            return [
                'newBid' => $autoBid,
                'tiedBidders' => $tiedBidders
            ];
        }

        // User's auto bid higher
        if ($autoBid > $competitorAutoBid) {
            $newBid = min($competitorAutoBid + $kb, $autoBid);
            
            $tiedBidders = LogBid::where('id_ikan_lelang', $idIkan)
                ->where('id_peserta', '!=', $logBid->id_peserta)
                ->whereNotNull('auto_bid')
                ->where('auto_bid', $newBid)
                ->lockForUpdate()
                ->get();

            return [
                'newBid' => $newBid,
                'tiedBidders' => $tiedBidders
            ];
        }

        // User's auto bid lower
        return [
            'newBid' => $autoBid,
            'tiedBidders' => []
        ];
    }

    private function shouldDisableManualBid($auth, $logBid, $currentPrice)
    {
        if (!$auth || !$logBid) {
            return false;
        }

        // Jika user punya auto bid aktif
        if ($logBid->auto_bid && $logBid->auto_bid > 0) {
            // Disable manual bid jika currentPrice masih di bawah auto bid limit
            return $currentPrice < $logBid->auto_bid;
        }

        return false;
    }

    public function bidNow($idIkan)
    {
        return redirect("/auction/$idIkan");
    }
}
