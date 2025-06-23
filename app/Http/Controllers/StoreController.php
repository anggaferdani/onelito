<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $item = $request->input('item', null);

        $auth = Auth::guard('member')->user();

        $kategori = $request->input('kategori', null);
        $search = $request->input('search', null);

        $products = Product::where('status_aktif', 1)
            ->selectRaw('*, CONCAT(merek_produk, " ", nama_produk) AS search_text')
            ->when($kategori !== null, function ($q) use ($kategori) {
                $q->where('id_kategori_produk', $kategori);
            })
            ->when($auth !== null, function ($q) use ($auth) {
                return $q->with([
                    'category',
                    'photo',
                    'wishlist' => fn ($w) => $w->where('id_peserta', $auth->id_peserta),
                ]);
            }, function ($q) {
                return $q->with(['category', 'photo']);
            })
            ->when($search !== null, function ($query) use ($search) {
                $query->whereRaw('CONCAT(merek_produk, " ", nama_produk) LIKE ?', ["%$search%"]);
            })
            ->orderByRaw('m_produk.urutan IS NULL, m_produk.urutan ASC')
            ->orderBy('m_produk.created_at', 'desc')
            ->paginate($this->perPage());


        $banners = Banner::all();

        $now = Carbon::now();
        $nowAkhir = Carbon::now()->subDays(2)->endOfDay();

        $nextAuction = Event::with(['auctionProducts' => function ($q) {
            $q->withCount('bidDetails')->with(['photo', 'maxBid', 'event']);
        }])
            ->where('tgl_mulai', '<=', $now)
            ->where('tgl_akhir', '>=', $nowAkhir)
            ->where('status_aktif', 1)
            ->where('status_tutup', 0)
            ->orderBy('tgl_mulai')
            ->get();

        $productCategories = ProductCategory::where('status_aktif', 1)->get();


        $category = ProductCategory::where('id_kategori_produk', $kategori)->first();

        return view('onelito_store', [
            'auth' => $auth,
            'products' => $products,
            'title' => 'ONELITO STORE',
            'kategori' => $request->input('kategori', null),
            'banners' => $banners,
            'auctions' => $nextAuction,
            'productCategories' => $productCategories,
            'category' => $category,
        ]);
    }

    public function detail($id)
    {
        $auth = Auth::guard('member')->user();

        $product = Product::
            with(['category', 'photo'])
            ->findOrFail($id);

        $isWishlisted = false;
        
        if ($auth) {
            $isWishlisted = Wishlist::where('id_peserta', $auth->id_peserta)
                ->where('wishlistable_id', $id)
                ->where('status_aktif', 1)
                ->exists();
        }

        return view('detail_onelito_store',[
            'auth' => $auth,
            'product' => $product,
            'isWishlisted' => $isWishlisted,
            'title' => 'ONELITO STORE'
        ]);
    }

    public function orderNow()
    {
        $item = $this->request->item;
        $ids= [];

        $sessionItems = session()->get('item');

        if ($sessionItems === null) {
            $items = [$item];
            session()->put('item', $items);
        }

        if ($sessionItems !== null) {
            $items = $sessionItems;

            if ($item !== null) {
                array_push($items, $item);
                array_unique($items);
                session()->put('item', $items);
            }
        }

        if ($items !== null) {
            $ids = collect($items)->unique()->values();
        }

        $auth = Auth::guard('member')->user();
        
        $products = Product::whereIn('id_produk', $ids)
        ->with('photo')
        ->get();

        return view('order-now',[
            'auth' => $auth,
            'title' => 'Transaksi Order',
            'products' => $products,
        ]);
    }

    public function cancelOrder()
    {
        session()->remove('item');

        return redirect('/onelito_store');
    }

    public function removeOrderNowItem($idProduct) {
        $items = session()->get('item');

        $res = array_diff(array_unique($items), [$idProduct]);

        if ($res !== null) {
            array_unique($res);
            array_values($res);
            array_filter($res);
        }


        session()->put('item', $res);


        return ['data' => $res, 'count' => count($res)];
    }

    public function checkOrderNow()
    {
        $sessionItems = session()->get('item');

        if ($sessionItems !== null) {
            return redirect('order-now');
        }

        return redirect('onelito_store');
    }
}
