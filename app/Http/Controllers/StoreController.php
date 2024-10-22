<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index()
    {
        $item = $this->request->input('item', null);

        $auth = Auth::guard('member')->user();

        $kategori = $this->request->input('kategori', null);
        $search = $this->request->input('search', null);

        $products = Product::
            where('status_aktif', 1)
            ->selectRaw('*, CONCAT(merek_produk, " ", nama_produk) AS search_text')
            ->when($kategori !== null, function ($q) use ($kategori){
                $q->where('id_kategori_produk', $kategori);
            })
            ->when($auth !== null, function ($q) use ($auth){
                return $q->with([
                    'category',
                    'photo',
                    'wishlist' => fn($w) => $w->where('id_peserta', $auth->id_peserta)]
                );
            }, function ($q) {
                return $q->with(['category', 'photo']);
            })
            ->when($search !== null, function ($query) use ($search){
                $query->whereRaw('CONCAT(merek_produk, " ", nama_produk) LIKE ?', ["%$search%"]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage());

        // $fishFoodProducts = Product::
        //     where('status_aktif', 1)
        //     ->where('id_kategori_produk', 2)
        //     ->when($auth !== null, function ($q) use ($auth){
        //         return $q->with([
        //             'category',
        //             'photo',
        //             'wishlist' => fn($w) => $w->where('id_peserta', $auth->id_peserta)]
        //         );
        //     }, function ($q) {
        //         return $q->with(['category', 'photo']);
        //     })
        //     ->orderBy('created_at', 'desc')
        //     ->paginate($this->perPage());

        // $fishEquipmentProducts = Product::
        //     where('status_aktif', 1)
        //     ->where('id_kategori_produk', 1)
        //     ->when($auth !== null, function ($q) use ($auth){
        //         return $q->with([
        //             'category',
        //             'photo',
        //             'wishlist' => fn($w) => $w->where('id_peserta', $auth->id_peserta)]
        //         );
        //     }, function ($q) {
        //         return $q->with(['category', 'photo']);
        //     })
        //     ->orderBy('created_at', 'desc')
        //     ->paginate($this->perPage());

        // $fishMedicineProducts = Product::
        //     where('status_aktif', 1)
        //     ->where('id_kategori_produk', 3)
        //     ->when($auth !== null, function ($q) use ($auth){
        //         return $q->with([
        //             'category',
        //             'photo',
        //             'wishlist' => fn($w) => $w->where('id_peserta', $auth->id_peserta)]
        //         );
        //     }, function ($q) {
        //         return $q->with(['category', 'photo']);
        //     })
        //     ->orderBy('created_at', 'desc')
        //     ->paginate($this->perPage());

        $fishFoodProducts = [];
        $fishEquipmentProducts = [];
        $fishMedicineProducts = [];

        return view('onelito_store',[
            'auth' => $auth,
            'products' => $products,
            // 'fishFoodProducts' => $fishFoodProducts,
            // 'fishEquipmentProducts' => $fishEquipmentProducts,
            // 'fishMedicineProducts' => $fishMedicineProducts,
            'title' => 'ONELITO STORE',
            'kategori' => $this->request->input('kategori', null)
        ]);
    }

    public function detail($id)
    {
        $auth = Auth::guard('member')->user();

        $product = Product::
            with(['category', 'photo'])
            ->findOrFail($id);

        return view('detail_onelito_store',[
            'auth' => $auth,
            'product' => $product,
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
