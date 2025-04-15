<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Setting;
use App\Models\OrderDetail;
use App\Models\ChampionFish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');

        $now = Carbon::now();
        $nowAkhir = Carbon::now()->subDays(2)->endOfDay();

        $auth = Auth::guard('member')->user();

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

        if (count($currentProducts) > 0) {
            foreach ($currentProducts as $product) {
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
        }

        $hotProductStores = Product::where('status_aktif', 1)
            ->when($auth !== null, function ($q) use ($auth){
                return $q->with([
                    'category',
                    'photo',
                    'wishlist' => fn($w) => $w->where('id_peserta', $auth->id_peserta)]
                );
            }, function ($q) {
                return $q->with(['category', 'photo']);
            })
            ->withCount('orderDetails')
            ->has('orderDetails', '>=', 1)
            ->orderByRaw('order_details_count desc')
            ->paginate(5);

        $championFishes = ChampionFish::where('status_aktif', 1)
            ->orderByRaw('created_at desc')
            ->paginate(4);

        Carbon::setLocale('id');

        $now = Carbon::now();

        $banners = Banner::all();

        return view('home',[
            "title" => "home",
            'now' => $now,
            'nextAuction' => $nextAuction,
            'hotProductStores' => $hotProductStores,
            'championFishes' => $championFishes,
            'auctionProducts' => $currentProducts,
            'auctions' => $nextAuction,
            'banners' => $banners,
        ]);
    }

    public function terms() {
        $setting = Setting::where('status', 'terms')->first();
        return view('new.pages.terms', compact('setting'));
    }

    public function privacy() {
        $setting = Setting::where('status', 'privacy')->first();
        return view('new.pages.privacy', compact('setting'));
    }
}
