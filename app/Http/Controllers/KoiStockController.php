<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\LogBid;
use App\Models\KoiStock;
use App\Models\Wishlist;
use App\Models\EventFish;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Auth;

class KoiStockController extends Controller
{
    public function index()
    {
        $auth = Auth::guard('member')->user();

        $fishes = KoiStock::
            where('status_aktif', 1)
            ->when($auth !== null, function ($q) use ($auth){
                return $q->with([
                    'wishlist' => fn($w) => $w->where('id_peserta', $auth->id_peserta)]
                );
            }, function ($q) {
                return $q;
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage());

            $banners = Banner::all();

        $now = Carbon::now();
        $nowAkhir = Carbon::now()->subDays(2)->endOfDay();

        $nextAuction = Event::with(['auctionProducts' => function ($q) {
            $q->withCount('bidDetails')->with(['photo', 'maxBid', 'event']);
        }
        ])
        ->where('tgl_mulai', '<=', $now)
        ->where('tgl_akhir', '>=', $nowAkhir)
        ->where('status_aktif', 1)
        ->where('status_tutup', 0)
        ->orderBy('tgl_mulai')
        ->get();

        $currentProducts = $nextAuction->pluck('auctionProducts')
        ->flatten(1)
        ->take(5);

        return view('koi_stok',[
            'auth' => $auth,
            'fishes' => $fishes,
            'title' => 'KOI STOCK',
            'banners' => $banners,
            'auctions' => $nextAuction,
        ]);
    }

    public function show($id)
    {
        $auth = Auth::guard('member')->user();

        $fish = KoiStock::
            where('status_aktif', 1)
            ->findOrFail($id);

        $isWishlisted = false;

        if ($auth) {
            $isWishlisted = Wishlist::where('id_peserta', $auth->id_peserta)->where('wishlistable_id', $id)->where('status_aktif', 1)->exists();
        }


        return view('detail_koistok',[
            'auth' => $auth,
            'fish' => $fish,
            'isWishlisted' => $isWishlisted,
            'title' => 'KOI STOCK'
        ]);
    }
}
