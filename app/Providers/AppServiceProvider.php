<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\EventFish;
use App\Models\KoiStock;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (env('APP_ENV', 'production') !== 'local') {
            $url->forceScheme('https');
        }

        $this->bootMorph();

    }

    public function bootMorph()
    {
        Relation::morphMap([
            Wishlist::Product => Product::class,
            Wishlist::EventFish => EventFish::class,
            Wishlist::KoiStock => KoiStock::class,

            Cart::Product => Product::class,
            Cart::EventFish => EventFish::class,
            Cart::KoiStock => KoiStock::class,

            OrderDetail::Product => Product::class,
            OrderDetail::EventFish => EventFish::class,
            OrderDetail::KoiStock => KoiStock::class,
        ]);
    }
}
