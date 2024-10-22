<?php

namespace App\Http\Controllers;

use App\Mail\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDF;

class CartController extends Controller
{
    public function store()
    {
        $auth = Auth::guard('member')->user();

        $dataCart = $this->request->only(['total_price', 'jumlah', 'cartable_id', 'cartable_type']);

        $exists = Cart::where('id_peserta', $auth->id_peserta)
            ->where('cartable_id', $dataCart['cartable_id'])
            ->where('cartable_type', $dataCart['cartable_type'])
            ->where('status_aktif', 1)
            ->first();

        if ($exists !== null) {
            $exists->jumlah = $exists->jumlah + $dataCart['jumlah'];
            $exists->save();

            return response()->json([
                'success' => true,
                'message' => 'Sukses Menambahkan Pemenang Lelang',
            ],200);
        }

        $dataCart['id_peserta'] = $auth->id_peserta;
        $dataCart['create_by'] = Auth::guard('admin')->id();
        $dataCart['update_by'] = Auth::guard('admin')->id();
        $dataCart['status_aktif'] = 1;

        $createCart = Cart::create($dataCart);

        if($createCart){
            return response()->json([
                'success' => true,
                'message' => 'Sukses Menambahkan Ke Keranjang',
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal Menambahkan Ke Keranjang'
            ],500);
        }
    }

    public function update($id)
    {
        $data = $this->request->only(['total_price', 'jumlah', 'cartable_id', 'cartable_type']);
        $cart = Cart::findOrFail($id);
        $cart->jumlah = $data['jumlah'];

        $cart->save();

        return response()->json([
            'success' => true,
        ],200);
    }

    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->status_aktif = 0;

        $cart->save();

        return response()->json([
            'success' => true,
        ],200);
    }

    public function show($id)
    {
        $auth = Auth::guard('member')->user();

        $order = Order::with('details.productable')->find($id);

        if ($order === null) {
            return redirect('/profil');
        }

        return view('transaksiweb',[
            'auth' => $auth,
            'order' => $order,
            'title' => 'Cart',
        ]);
    }

    public function order()
    {
        $method = $this->request->input('method', null);

        $auth = Auth::guard('member')->user();
        $data = $this->request->all();
        $now = Carbon::now();

        $dataOrder['no_order'] = $now->format('Ymd');
        $dataOrder['tanggal'] = $now;
        $dataOrder['status'] = 'Menunggu Dikirim';
        $dataOrder['total'] = $data['total'];
        $dataOrder['status_aktif'] = 1;

        $order = Order::create($dataOrder);
        $order->no_order = $order->no_order.$order->id_order;
        $order->save();

        // only use for checkout from payment cart
        if ($method === null) {
            Cart::whereIn('id_keranjang', collect($data['data_order'])->pluck('id_keranjang'))->update([
                'status_aktif' => 0,
            ]);
        }

        foreach ($data['data_order'] as $dOrder) {
            if ($dOrder['id'] === "0") {
                continue;
            }

            OrderDetail::create([
                'status_aktif' => 1,
                'total' => $dOrder['price'],
                'id_order' => $order->id_order,
                'id_peserta' => $auth->id_peserta,
                'jumlah_produk' => $dOrder['total_produk'],
                'productable_id' => $dOrder['id'],
                'productable_type' => $dOrder['type'],
            ]);
        }

        session()->remove('item');
    
        // temporary disable 
        Mail::to('onelito.koi@gmail.com')->send(new OrderRequest($order));

        return response()->json([
            'success' => true,
            'id' => $order->id_order,
        ],200);
    }

    public function pdf($id)
    {
        $auth = Auth::guard('member')->user();

        $order = Order::find($id);

        if ($order === null) {
            return redirect('/profil');
        }

        $data = [
            'auth' => $auth,
            'order' => $order,
            'title' => 'Cart',
        ];

        $pdf = PDF::loadView('transaksiweb-pdf', $data);

        return $pdf->download('invoice.pdf');
    }
}
