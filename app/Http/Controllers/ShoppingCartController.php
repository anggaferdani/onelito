<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingCartController extends Controller
{
    public function semua() {
        $auth = Auth::guard('member')->user();
        Order::where('id_peserta', $auth->id_peserta)->where('status_order', 'pending')->where('expiry_date', '<', now())->update(['status_order' => 'cancel']);

        $orders = Order::where('id_peserta', $auth->id_peserta)->where('status_aktif', 1)->latest()->paginate(10);

        return view('new.pages.shopping-cart.semua', compact(
            'orders',
        ));
    }

    public function belumDibayar() {
        $auth = Auth::guard('member')->user();
        Order::where('id_peserta', $auth->id_peserta)->where('status_order', 'pending')->where('expiry_date', '<', now())->update(['status_order' => 'cancel']);

        $orders = Order::where('id_peserta', $auth->id_peserta)->where('status_order', 'pending')->where('status_aktif', 1)->latest()->paginate(10);

        return view('new.pages.shopping-cart.semua', compact(
            'orders',
        ));
    }

    public function menungguKonfirmasi() {
        $auth = Auth::guard('member')->user();

        $orders = Order::where('id_peserta', $auth->id_peserta)->where('status_order', 'paid')->where('status_aktif', 1)->latest()->paginate(10);

        return view('new.pages.shopping-cart.semua', compact(
            'orders',
        ));
    }

    public function sedangDiproses() {
        $auth = Auth::guard('member')->user();

        $orders = Order::where('id_peserta', $auth->id_peserta)->where('status_order', 'process')->where('status_aktif', 1)->latest()->paginate(10);

        return view('new.pages.shopping-cart.semua', compact(
            'orders',
        ));
    }

    public function dikirim() {
        $auth = Auth::guard('member')->user();

        $orders = Order::where('id_peserta', $auth->id_peserta)->where('status_order', 'delivered')->where('status_aktif', 1)->latest()->paginate(10);

        return view('new.pages.shopping-cart.semua', compact(
            'orders',
        ));
    }

    public function selesai() {
        $auth = Auth::guard('member')->user();

        $orders = Order::where('id_peserta', $auth->id_peserta)->where('status_order', 'done')->where('status_aktif', 1)->latest()->paginate(10);

        return view('new.pages.shopping-cart.semua', compact(
            'orders',
        ));
    }

    public function dibatalkan() {
        $auth = Auth::guard('member')->user();

        $orders = Order::where('id_peserta', $auth->id_peserta)->where('status_order', 'cancel')->where('status_aktif', 1)->latest()->paginate(10);

        return view('new.pages.shopping-cart.semua', compact(
            'orders',
        ));
    }
}
