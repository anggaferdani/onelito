<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Event;
use App\Models\Order;
use App\Models\Alamat;
use App\Models\Member;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\EventFish;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProfileController extends Controller
{
    public function edit(Request $request) {
        $auth = Auth::guard('member')->user();

        try {
            $request->validate([
                'profile_pic' => 'dimensions:ratio=1/1',
            ], [
                'profile_pic.dimensions' => 'Foto profil harus memiliki dimensi rasio 1:1 (persegi).',
            ]);

            $peserta = Member::find($auth->id_peserta);

            $array = [
                'nama_depan' => $request->nama_depan,
                'nama_belakang' => $request->nama_belakang,
                'nama' => $request->nama_depan . ' ' . $request->nama_belakang,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
            ];

            if($request['password']){
                $array['password'] = bcrypt($request['password']);
            }

            if ($request->hasFile('profile_pic')) {
                $file = $request->file('profile_pic');
                $directory = 'storage/foto_profile/' . $auth->id_peserta . '/';
                $fileName = 'foto_profile/' . $auth->id_peserta . '/' . date('YmdHis') . rand(999999999, 9999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($directory), $fileName);
                $array['profile_pic'] = $fileName;
            }

            $peserta->update($array);

            return redirect()->back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function tulisCatatan(Request $request, $id_keranjang) {
        $cart = Cart::findOrFail($id_keranjang);
        $cart->update([
            'catatan' => $request->catatan,
        ]);
        
        return redirect()->back()->with('success', 'Success');
    }

    public function tambahBarang($id_keranjang) {
        $cart = Cart::findOrFail($id_keranjang);
        $cart->update([
            'jumlah' => $cart->jumlah + 1
        ]);
    
        return response()->json([
            'success' => true,
            'jumlah' => $cart->jumlah
        ]);
    }
    
    public function hapusBarang($id_keranjang) {
        $cart = Cart::findOrFail($id_keranjang);
    
        if ($cart->jumlah > 1) {
            $cart->update([
                'jumlah' => $cart->jumlah - 1
            ]);
        } else {
            $cart->update([
                'status_aktif' => 0,
                'jumlah' => $cart->jumlah - 1
            ]);
        }
    
        return response()->json([
            'success' => true,
            'jumlah' => $cart->jumlah,
            'status_aktif' => $cart->status_aktif
        ]);
    }

    public function cart() {
        $auth = Auth::guard('member')->user();
        $carts = Cart::where('id_peserta', $auth->id_peserta)->whereNot('cartable_type', 'EventFish')->where('status_aktif', 1)->where('jumlah', '>', 0)->latest()->get();
        return view('new.pages.profile.cart', compact(
            'auth',
            'carts',
        ));
    }

    public function winningAuction() {
        $auth = Auth::guard('member')->user();
        $carts = Cart::where('id_peserta', $auth->id_peserta)->where('cartable_type', 'EventFish')->where('status_aktif', 1)->latest()->get();
        return view('new.pages.profile.winning-auction', compact(
            'auth',
            'carts',
        ));
    }

    // public function getCouriers()
    // {
    //     $response = Http::withToken(env('BITESHIP_API_KEY'))
    //         ->get('https://api.biteship.com/v1/maps/areas?countries=ID&input=Bogor');

    //     if ($response->successful()) {
    //         return $response->json([]);
    //     }

    //     return [];
    // }

    public function getCouriers()
    {
        $response = Http::withToken(env('BITESHIP_API_KEY'))
            ->get('https://api.biteship.com/v1/couriers');

        // if ($response->successful()) {
        //     return $response->json('couriers');
        // }

        if ($response->successful()) {
            return collect($response->json('couriers'))->filter(function ($courier) {
                return in_array($courier['courier_code'], ['jne', 'jnt', 'sicepat', 'gojek', 'grab', 'anteraja']);
            })->values()->all();
        }

        return [];
    }

    public function shipment(Request $request) {
        $auth = Auth::guard('member')->user();
        $cartIds = explode(',', $request->query('ids', ''));
        $carts = Cart::where('id_peserta', $auth->id_peserta)
                ->where('status_aktif', 1)
                ->where('jumlah', '>', 0)
                ->whereIn('id_keranjang', $cartIds)
                ->get();
        $totalJumlahBarang = $carts->sum('jumlah');
        $alamats = Alamat::where('peserta_id', $auth->id_peserta)->where('status', 1)->get();
        $couriers = $this->getCouriers();
        // dd($couriers);
        
        $shipper = Alamat::where('id', 1)->first();
        $destination = Alamat::where('id', $auth->pilih_alamat)->where('peserta_id', $auth->id_peserta)->where('status', 1)->first();

        return view('new.pages.profile.shipment', compact(
            'auth',
            'carts',
            'alamats',
            'totalJumlahBarang',
            'couriers',
            'shipper',
            'destination',
        ));
    }

    public function card() {
        return view('new.pages.profile.card');
    }

    public function member() {
        $auth = Auth::guard('member')->user();

        try {
            $peserta = Member::find($auth->id_peserta);

            $uniqueNumber = $this->generateUniqueNumber();

            $peserta->update([
                'member' => $uniqueNumber,
            ]);

            return redirect()->back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    private function generateUniqueNumber() {
        do {
            $number = str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
        } while (Member::where('member', $number)->exists());
    
        return $number;
    }

    public function index()
    {
        $auth = Auth::guard('member')->user();

        Carbon::setLocale('id');
        $now = Carbon::now();
        $nowAkhir = Carbon::now()->subDays(3)->endOfDay();

        $currentAuctions = Event::with(['auctionProducts'=> function ($q) {
            $q->with(['maxBid']);
        }])
        ->where('tgl_mulai', '<=', $now)
        ->where('tgl_akhir', '>=', $nowAkhir)
        ->where('status_aktif', 1)
        ->orderBy('tgl_mulai')
        ->orderBy('created_at', 'desc')
        ->get();

        $currentProducts = $currentAuctions->pluck('auctionProducts')
        ->flatten(1);

        $wishlists = [];
        $getWishlist = $auth->wishlists()
        ->with(['wishlistable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo', 'maxBid'],
            ]);
        }])->get();

        $wishEventFish = $getWishlist->whereIn('wishlistable_id', $currentProducts->pluck('id_ikan'));
        $products = $getWishlist->where('wishlistable_type', Wishlist::Product);

        $wishlists = $products->merge($wishEventFish);

        // $currentAuctionsFinised = Event::with(['auctionProducts'=> function ($q) {
        //     $q->with(['maxBid']);
        // }])
        // ->where('tgl_mulai', '<=', $now)
        // ->where('tgl_akhir', '<=', $now)
        // ->where('status_aktif', 1)
        // ->get();

        // $currentProductsFinised = $currentAuctionsFinised->pluck('auctionProducts')
        // ->flatten(1);

        $auctionProducts = EventFish::
        doesntHave('cartable')
        ->whereHas('event', function($q) use ($now){
            $q->where('tgl_akhir', '<', $now);
        })
        ->whereNotNull('id_event')
        ->with(['maxBid', 'event'])
        ->where('status_aktif', 1)->get()
        ->mapWithKeys(fn($a) => [$a->id_ikan => $a]);

        $fishInCart = Cart::whereIn('cartable_id', $auctionProducts->pluck('id_ikan'))
            ->where('cartable_type', Cart::EventFish)
            ->get()
            ->mapWithKeys(fn($q)=>[$q->cartable_id => $q]);

        foreach ($auctionProducts as $cProduct) {
            if ($cProduct->maxBid === null) {
                continue;
            }

            if ($cProduct->maxBid->id_peserta !== $auth->id_peserta) {
                continue;
            }

            $dateDiff = Carbon::parse($now, 'id')->diffInMinutes($cProduct->maxBid->updated_at);

            $dateEventEnd = Carbon::parse($cProduct->event->tgl_akhir)->addMinutes($cProduct->extra_time);

            if ($now < $dateEventEnd) {
                continue;
            }

            if ($dateDiff < $cProduct->extra_time || array_key_exists($cProduct->id_ikan, $fishInCart->toArray())) {
                continue;
            }

            $dataCart['status_aktif'] = 1;

            $dataCart['id_peserta'] = $auth->id_peserta;
            $dataCart['cartable_id'] = $cProduct->id_ikan;
            $dataCart['jumlah'] = 1;
            $dataCart['cartable_type'] = Cart::EventFish;

            Cart::create($dataCart);
        }

        $carts = $auth->carts()
        ->with(['cartable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                EventFish::class => ['photo'],
            ]);
        }])
        ->where('cartable_type', Cart::EventFish)
        ->where('status_aktif', 1)
        ->get();

        $cartFishes = $carts->where('cartable_type', Cart::EventFish);

        if ($cartFishes->count() > 0) {
            $biddings = $auth->biddings()->whereIn('id_ikan_lelang', $cartFishes->pluck('cartable_id'))->get()
            ->mapWithKeys(fn($q) => [$q['id_ikan_lelang']  => $q]);

            foreach ($carts as $cart) {
                if (!array_key_exists($cart->cartable_id, $biddings->toArray())) {
                    continue;
                }

                $cart->price = $biddings[$cart->cartable_id]->nominal_bid;
            }
        }

        $orders = $auth->orders()->with(['productable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo'],
            ]);
        }])
        ->where('status_aktif', 1)->get();

        $storeCarts = $auth->carts()
        ->with(['cartable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
            ]);
        }])
        ->whereIn('cartable_type', [Cart::Product, Cart::KoiStock])
        ->where('status_aktif', 1)
        ->get();

        $title = 'profil';
        $section = $this->request->input('section', null);
        if ($section == "cart") {
            $title = 'cart';
        }

        if ($section == "store-cart") {
            $title = 'store_cart';
        }

        return view('new.pages.profile.profile',[
            'auth' => $auth,
            'title' => $title,
            'wishlists' => $wishlists,
            'carts' => $carts,
            'storeCarts' => $storeCarts,
            'orders' => $orders,
        ]);
    }

    public function shopcart()
    {
        $auth = Auth::guard('member')->user();

        Carbon::setLocale('id');
        $now = Carbon::now();
        $nowAkhir = Carbon::now()->subDays(3)->endOfDay();

        $currentAuctions = Event::with(['auctionProducts'=> function ($q) {
            $q->with(['maxBid']);
        }])
        ->where('tgl_mulai', '<=', $now)
        ->where('tgl_akhir', '>=', $nowAkhir)
        ->where('status_aktif', 1)
        ->orderBy('tgl_mulai')
        ->orderBy('created_at', 'desc')
        ->get();

        $currentProducts = $currentAuctions->pluck('auctionProducts')
        ->flatten(1);

        $wishlists = [];
        $getWishlist = $auth->wishlists()
        ->with(['wishlistable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo', 'maxBid'],
            ]);
        }])->get();

        $wishEventFish = $getWishlist->whereIn('wishlistable_id', $currentProducts->pluck('id_ikan'));
        $products = $getWishlist->where('wishlistable_type', Wishlist::Product);

        $wishlists = $products->merge($wishEventFish);

        // $currentAuctionsFinised = Event::with(['auctionProducts'=> function ($q) {
        //     $q->with(['maxBid']);
        // }])
        // ->where('tgl_mulai', '<=', $now)
        // ->where('tgl_akhir', '<=', $nowAkhir)
        // ->where('status_aktif', 1)
        // ->get();

        // $currentProductsFinised = $currentAuctionsFinised->pluck('auctionProducts')
        // ->flatten(1);

        $auctionProducts = EventFish::
        doesntHave('cartable')
        ->whereHas('event', function($q) use ($now){
            $q->where('tgl_akhir', '<', $now);
        })
        ->whereNotNull('id_event')
        ->with(['maxBid', 'event'])
        ->where('status_aktif', 1)->get()
        ->mapWithKeys(fn($a) => [$a->id_ikan => $a]);

        $fishInCart = Cart::whereIn('cartable_id', $auctionProducts->pluck('id_ikan'))
            ->where('cartable_type', Cart::EventFish)
            ->get()
            ->mapWithKeys(fn($q)=>[$q->cartable_id => $q]);

        foreach ($auctionProducts as $cProduct) {
            if ($cProduct->maxBid === null) {
                continue;
            }

            if ($cProduct->maxBid->id_peserta !== $auth->id_peserta) {
                continue;
            }

            $dateDiff = Carbon::parse($now, 'id')->diffInMinutes($cProduct->maxBid->updated_at);

            $dateEventEnd = Carbon::parse($cProduct->event->tgl_akhir)->addMinutes($cProduct->extra_time);

            if ($now < $dateEventEnd) {
                continue;
            }

            if ($dateDiff < $cProduct->extra_time || array_key_exists($cProduct->id_ikan, $fishInCart->toArray())) {
                continue;
            }

            $dataCart['status_aktif'] = 1;

            $dataCart['id_peserta'] = $auth->id_peserta;
            $dataCart['cartable_id'] = $cProduct->id_ikan;
            $dataCart['jumlah'] = 1;
            $dataCart['cartable_type'] = Cart::EventFish;

            Cart::create($dataCart);
        }

        $carts = $auth->carts()
        ->with(['cartable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                EventFish::class => ['photo'],
            ]);
        }])
        ->where('cartable_type', Cart::EventFish)
        ->where('status_aktif', 1)
        ->get();

        $cartFishes = $carts->where('cartable_type', Cart::EventFish);

        if ($cartFishes->count() > 0) {
            $biddings = $auth->biddings()->whereIn('id_ikan_lelang', $cartFishes->pluck('cartable_id'))->get()
            ->mapWithKeys(fn($q) => [$q['id_ikan_lelang']  => $q]);

            foreach ($carts as $cart) {
                if (!array_key_exists($cart->cartable_id, $biddings->toArray())) {
                    continue;
                }

                $cart->price = $biddings[$cart->cartable_id]->nominal_bid;
            }
        }

        $orders = $auth->orders()->with(['productable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo'],
            ]);
        }])
        ->where('status_aktif', 1)->get();

        $storeCarts = $auth->carts()
        ->with(['cartable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
            ]);
        }])
        ->whereIn('cartable_type', [Cart::Product, Cart::KoiStock])
        ->where('status_aktif', 1)
        ->get();

        $title = 'Shopping Cart';

        

        return view('shoppingcart',[
            'auth' => $auth,
            'title' => 'Shopping Cart',
            'carts' => $carts,
            'storeCarts' => $storeCarts,
            'wishlists' => $wishlists,
            'orders' => $orders
        ]);
    }

    public function storecart()
    {
        $auth = Auth::guard('member')->user();

        Carbon::setLocale('id');
        $now = Carbon::now();
        $nowAkhir = Carbon::now()->subDays(3)->endOfDay();

        $currentAuctions = Event::with(['auctionProducts'=> function ($q) {
            $q->with(['maxBid']);
        }])
        ->where('tgl_mulai', '<=', $now)
        ->where('tgl_akhir', '>=', $nowAkhir)
        ->where('status_aktif', 1)
        ->orderBy('tgl_mulai')
        ->orderBy('created_at', 'desc')
        ->get();

        $currentProducts = $currentAuctions->pluck('auctionProducts')
        ->flatten(1);

        $wishlists = [];
        $getWishlist = $auth->wishlists()
        ->with(['wishlistable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo', 'maxBid'],
            ]);
        }])->get();

        $wishEventFish = $getWishlist->whereIn('wishlistable_id', $currentProducts->pluck('id_ikan'));
        $products = $getWishlist->where('wishlistable_type', Wishlist::Product);

        $wishlists = $products->merge($wishEventFish);

        // $currentAuctionsFinised = Event::with(['auctionProducts'=> function ($q) {
        //     $q->with(['maxBid']);
        // }])
        // ->where('tgl_mulai', '<=', $now)
        // ->where('tgl_akhir', '<=', $nowAkhir)
        // ->where('status_aktif', 1)
        // ->get();

        // $currentProductsFinised = $currentAuctionsFinised->pluck('auctionProducts')
        // ->flatten(1);

        $auctionProducts = EventFish::
        doesntHave('cartable')
        ->whereHas('event', function($q) use ($now){
            $q->where('tgl_akhir', '<', $now);
        })
        ->whereNotNull('id_event')
        ->with(['maxBid', 'event'])
        ->where('status_aktif', 1)->get()
        ->mapWithKeys(fn($a) => [$a->id_ikan => $a]);

        $fishInCart = Cart::whereIn('cartable_id', $auctionProducts->pluck('id_ikan'))
            ->where('cartable_type', Cart::EventFish)
            ->get()
            ->mapWithKeys(fn($q)=>[$q->cartable_id => $q]);

        foreach ($auctionProducts as $cProduct) {
            if ($cProduct->maxBid === null) {
                continue;
            }

            if ($cProduct->maxBid->id_peserta !== $auth->id_peserta) {
                continue;
            }

            $dateDiff = Carbon::parse($now, 'id')->diffInMinutes($cProduct->maxBid->updated_at);

            $dateEventEnd = Carbon::parse($cProduct->event->tgl_akhir)->addMinutes($cProduct->extra_time);

            if ($now < $dateEventEnd) {
                continue;
            }

            if ($dateDiff < $cProduct->extra_time || array_key_exists($cProduct->id_ikan, $fishInCart->toArray())) {
                continue;
            }

            $dataCart['status_aktif'] = 1;

            $dataCart['id_peserta'] = $auth->id_peserta;
            $dataCart['cartable_id'] = $cProduct->id_ikan;
            $dataCart['jumlah'] = 1;
            $dataCart['cartable_type'] = Cart::EventFish;

            Cart::create($dataCart);
        }

        $carts = $auth->carts()
        ->with(['cartable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                EventFish::class => ['photo'],
            ]);
        }])
        ->where('cartable_type', Cart::EventFish)
        ->where('status_aktif', 1)
        ->get();

        $cartFishes = $carts->where('cartable_type', Cart::EventFish);

        if ($cartFishes->count() > 0) {
            $biddings = $auth->biddings()->whereIn('id_ikan_lelang', $cartFishes->pluck('cartable_id'))->get()
            ->mapWithKeys(fn($q) => [$q['id_ikan_lelang']  => $q]);

            foreach ($carts as $cart) {
                if (!array_key_exists($cart->cartable_id, $biddings->toArray())) {
                    continue;
                }

                $cart->price = $biddings[$cart->cartable_id]->nominal_bid;
            }
        }

        $orders = $auth->orders()->with(['productable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo'],
            ]);
        }])
        ->where('status_aktif', 1)->get();

        $storeCarts = $auth->carts()
        ->with(['cartable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
            ]);
        }])
        ->whereIn('cartable_type', [Cart::Product, Cart::KoiStock])
        ->where('status_aktif', 1)
        ->get();

        return view('storecart',[
            'auth' => $auth,
            'title' => 'Store Cart',
            'carts' => $carts,
            'storeCarts' => $storeCarts,
            'wishlists' => $wishlists,
            'orders' => $orders
        ]);
    }

    public function wishlist()
    {
        $auth = Auth::guard('member')->user();

        $now = Carbon::now();
        $nowAkhir = Carbon::now()->subDays(3)->endOfDay();

        $currentAuctions = Event::with(['auctionProducts'])
            ->where('tgl_mulai', '<=', $now)
            ->where('tgl_akhir', '>=', $nowAkhir)
            ->where('status_aktif', 1)
            ->orderBy('tgl_mulai')
            ->orderBy('created_at', 'desc')
            ->get();

        $currentProducts = $currentAuctions->pluck('auctionProducts')
        ->flatten(1);

        $wishlists = [];
        $getWishlist = $auth->wishlists()
        ->with(['wishlistable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo', 'maxBid'],
            ]);
        }])->get();

        $wishEventFish = $getWishlist->whereIn('wishlistable_id', $currentProducts->pluck('id_ikan'));
        $products = $getWishlist->where('wishlistable_type', Wishlist::Product);

        $wishlists = $products->merge($wishEventFish);

        $carts = $auth->carts()
        ->with(['cartable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo'],
            ]);
        }])->get();

        $cartFishes = $carts->where('cartable_type', Cart::EventFish);

        if ($cartFishes->count() > 0) {
            $biddings = $auth->biddings()->whereIn('id_ikan_lelang', $cartFishes->pluck('cartable_id'))->get()
            ->mapWithKeys(fn($q) => [$q['id_ikan_lelang']  => $q]);

            foreach ($carts as $cart) {
                if (!array_key_exists($cart->cartable_id, $biddings->toArray())) {
                    continue;
                }

                $cart->price = $biddings[$cart->cartable_id]->nominal_bid;
            }
        }

        $orders = $auth->orders()->with(['productable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo'],
            ]);
        }])
        ->where('status_aktif', 1)->get();

        return view('wishlist',[
            'auth' => $auth,
            'title' => 'wishlist',
            'carts' => $carts,
            'wishlists' => $wishlists,
            'orders' => $orders
        ]);
    }

    public function purchase()
    {
        $auth = Auth::guard('member')->user();

        $now = Carbon::now();
        $nowAkhir = Carbon::now()->subDays(3)->endOfDay();

        $currentAuctions = Event::with(['auctionProducts'])
            ->where('tgl_mulai', '<=', $now)
            ->where('tgl_akhir', '>=', $nowAkhir)
            ->where('status_aktif', 1)
            ->orderBy('tgl_mulai')
            ->orderBy('created_at', 'desc')
            ->get();

        $currentProducts = $currentAuctions->pluck('auctionProducts')
        ->flatten(1);

        $wishlists = [];
        $getWishlist = $auth->wishlists()
        ->with(['wishlistable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo', 'maxBid'],
            ]);
        }])->get();

        $wishEventFish = $getWishlist->whereIn('wishlistable_id', $currentProducts->pluck('id_ikan'));
        $products = $getWishlist->where('wishlistable_type', Wishlist::Product);

        $wishlists = $products->merge($wishEventFish);

        $carts = $auth->carts()
        ->with(['cartable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo'],
            ]);
        }])->get();

        $cartFishes = $carts->where('cartable_type', Cart::EventFish);

        if ($cartFishes->count() > 0) {
            $biddings = $auth->biddings()->whereIn('id_ikan_lelang', $cartFishes->pluck('cartable_id'))->get()
            ->mapWithKeys(fn($q) => [$q['id_ikan_lelang']  => $q]);

            foreach ($carts as $cart) {
                if (!array_key_exists($cart->cartable_id, $biddings->toArray())) {
                    continue;
                }

                $cart->price = $biddings[$cart->cartable_id]->nominal_bid;
            }
        }

        $orders = $auth->orders()->with(['productable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo'],
            ]);
        }])
        ->where('status_aktif', 1)->get();

        return view('purchase',[
            'auth' => $auth,
            'title' => 'purchase',
            'carts' => $carts,
            'wishlists' => $wishlists,
            'orders' => $orders,
        ]);
    }

    public function viewChangePassword()
    {
        $auth = Auth::guard('member')->user();

        Carbon::setLocale('id');
        $now = Carbon::now();
        $nowAkhir = Carbon::now()->subDays(3)->endOfDay();

        $currentAuctions = Event::with(['auctionProducts'=> function ($q) {
            $q->with(['maxBid']);
        }])
        ->where('tgl_mulai', '<=', $now)
        ->where('tgl_akhir', '>=', $nowAkhir)
        ->where('status_aktif', 1)
        ->orderBy('tgl_mulai')
        ->orderBy('created_at', 'desc')
        ->get();

        $currentProducts = $currentAuctions->pluck('auctionProducts')
        ->flatten(1);

        $wishlists = [];
        $getWishlist = $auth->wishlists()
        ->with(['wishlistable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo', 'maxBid'],
            ]);
        }])->get();

        $wishEventFish = $getWishlist->whereIn('wishlistable_id', $currentProducts->pluck('id_ikan'));
        $products = $getWishlist->where('wishlistable_type', Wishlist::Product);

        $wishlists = $products->merge($wishEventFish);

        $auctionProducts = EventFish::
        doesntHave('cartable')
        ->whereHas('event', function($q) use ($now){
            $q->where('tgl_akhir', '<', $now);
        })
        ->whereNotNull('id_event')
        ->with(['maxBid', 'event'])
        ->where('status_aktif', 1)->get()
        ->mapWithKeys(fn($a) => [$a->id_ikan => $a]);

        $fishInCart = Cart::whereIn('cartable_id', $auctionProducts->pluck('id_ikan'))
            ->where('cartable_type', Cart::EventFish)
            ->get()
            ->mapWithKeys(fn($q)=>[$q->cartable_id => $q]);

        foreach ($auctionProducts as $cProduct) {
            if ($cProduct->maxBid === null) {
                continue;
            }

            if ($cProduct->maxBid->id_peserta !== $auth->id_peserta) {
                continue;
            }

            $dateDiff = Carbon::parse($now, 'id')->diffInMinutes($cProduct->maxBid->updated_at);

            $dateEventEnd = Carbon::parse($cProduct->event->tgl_akhir)->addMinutes($cProduct->extra_time);

            if ($now < $dateEventEnd) {
                continue;
            }

            if ($dateDiff < $cProduct->extra_time || array_key_exists($cProduct->id_ikan, $fishInCart->toArray())) {
                continue;
            }

            $dataCart['status_aktif'] = 1;

            $dataCart['id_peserta'] = $auth->id_peserta;
            $dataCart['cartable_id'] = $cProduct->id_ikan;
            $dataCart['jumlah'] = 1;
            $dataCart['cartable_type'] = Cart::EventFish;

            Cart::create($dataCart);
        }

        $carts = $auth->carts()
        ->with(['cartable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo'],
            ]);
        }])
        ->where('status_aktif', 1)
        ->get();

        $cartFishes = $carts->where('cartable_type', Cart::EventFish);

        if ($cartFishes->count() > 0) {
            $biddings = $auth->biddings()->whereIn('id_ikan_lelang', $cartFishes->pluck('cartable_id'))->get()
            ->mapWithKeys(fn($q) => [$q['id_ikan_lelang']  => $q]);

            foreach ($carts as $cart) {
                if (!array_key_exists($cart->cartable_id, $biddings->toArray())) {
                    continue;
                }

                $cart->price = $biddings[$cart->cartable_id]->nominal_bid;
            }
        }

        $orders = $auth->orders()->with(['productable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo'],
            ]);
        }])
        ->where('status_aktif', 1)->get();

        $storeCarts = $auth->carts()
        ->with(['cartable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
            ]);
        }])
        ->whereIn('cartable_type', [Cart::Product, Cart::KoiStock])
        ->where('status_aktif', 1)
        ->get();

        return view('ganti_password',[
            'auth' => $auth,
            'title' => 'Change Password',
            'carts' => $carts,
            'wishlists' => $wishlists,
            'orders' => $orders,
            'storeCarts' => $storeCarts,
        ]);
    }

    public function changeProfile()
    {
        $this->request->validate([
            'foto' => 'required',
        ]);

        $member = Member::findOrFail(Auth::guard('member')->user()->id_peserta);

        $image = $member->profile_pic;
        if($this->request->hasFile('foto')){
            $image = $this->request->file('foto')->store(
                "foto_profile/$member->id_peserta",'public'
            );
        }

        $member->profile_pic = $image;
        $member->save();

        return response()
        ->json("Profile picture changed successfully!");
    }

    public function updateProfile()
    {

        $member = Member::findOrFail(Auth::guard('member')->user()->id_peserta);

        $member->nama = $this->request->input('name');
        $member->no_hp = $this->request->input('no_hp');
        $member->alamat = $this->request->input('alamat');
        $member->save();

        return redirect()->back()->with([
            'success' => true,
            'message' => 'Data berhasil di update'
        ],200);
    }

    public function viewUpdateProfile()
    {
        $auth = Auth::guard('member')->user();

        Carbon::setLocale('id');
        $now = Carbon::now();
        $nowAkhir = Carbon::now()->subDays(3)->endOfDay();

        $currentAuctions = Event::with(['auctionProducts'=> function ($q) {
            $q->with(['maxBid']);
        }])
        ->where('tgl_mulai', '<=', $now)
        ->where('tgl_akhir', '>=', $nowAkhir)
        ->where('status_aktif', 1)
        ->orderBy('tgl_mulai')
        ->orderBy('created_at', 'desc')
        ->get();

        $currentProducts = $currentAuctions->pluck('auctionProducts')
        ->flatten(1);

        $wishlists = [];
        $getWishlist = $auth->wishlists()
        ->with(['wishlistable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo', 'maxBid'],
            ]);
        }])->get();

        $wishEventFish = $getWishlist->whereIn('wishlistable_id', $currentProducts->pluck('id_ikan'));
        $products = $getWishlist->where('wishlistable_type', Wishlist::Product);

        $wishlists = $products->merge($wishEventFish);

        $auctionProducts = EventFish::
        doesntHave('cartable')
        ->whereHas('event', function($q) use ($now){
            $q->where('tgl_akhir', '<', $now);
        })
        ->whereNotNull('id_event')
        ->with(['maxBid', 'event'])
        ->where('status_aktif', 1)->get()
        ->mapWithKeys(fn($a) => [$a->id_ikan => $a]);

        $fishInCart = Cart::whereIn('cartable_id', $auctionProducts->pluck('id_ikan'))
            ->where('cartable_type', Cart::EventFish)
            ->get()
            ->mapWithKeys(fn($q)=>[$q->cartable_id => $q]);

        foreach ($auctionProducts as $cProduct) {
            if ($cProduct->maxBid === null) {
                continue;
            }

            if ($cProduct->maxBid->id_peserta !== $auth->id_peserta) {
                continue;
            }

            $dateDiff = Carbon::parse($now, 'id')->diffInMinutes($cProduct->maxBid->updated_at);

            $dateEventEnd = Carbon::parse($cProduct->event->tgl_akhir)->addMinutes($cProduct->extra_time);

            if ($now < $dateEventEnd) {
                continue;
            }

            if ($dateDiff < $cProduct->extra_time || array_key_exists($cProduct->id_ikan, $fishInCart->toArray())) {
                continue;
            }

            $dataCart['status_aktif'] = 1;

            $dataCart['id_peserta'] = $auth->id_peserta;
            $dataCart['cartable_id'] = $cProduct->id_ikan;
            $dataCart['jumlah'] = 1;
            $dataCart['cartable_type'] = Cart::EventFish;

            Cart::create($dataCart);
        }

        $carts = $auth->carts()
        ->with(['cartable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo'],
            ]);
        }])
        ->where('status_aktif', 1)
        ->get();

        $cartFishes = $carts->where('cartable_type', Cart::EventFish);

        if ($cartFishes->count() > 0) {
            $biddings = $auth->biddings()->whereIn('id_ikan_lelang', $cartFishes->pluck('cartable_id'))->get()
            ->mapWithKeys(fn($q) => [$q['id_ikan_lelang']  => $q]);

            foreach ($carts as $cart) {
                if (!array_key_exists($cart->cartable_id, $biddings->toArray())) {
                    continue;
                }

                $cart->price = $biddings[$cart->cartable_id]->nominal_bid;
            }
        }

        $orders = $auth->orders()->with(['productable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
                EventFish::class => ['photo'],
            ]);
        }])
        ->where('status_aktif', 1)->get();


        $storeCarts = $auth->carts()
        ->with(['cartable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Product::class => ['photo'],
            ]);
        }])
        ->whereIn('cartable_type', [Cart::Product, Cart::KoiStock])
        ->where('status_aktif', 1)
        ->get();

        return view('update_profile',[
            'auth' => $auth,
            'title' => 'Update Profile',
            'carts' => $carts,
            'wishlists' => $wishlists,
            'orders' => $orders,
            'storeCarts' => $storeCarts,
        ]);
    }
}
