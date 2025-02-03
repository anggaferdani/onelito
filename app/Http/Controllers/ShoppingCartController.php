<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tracking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShoppingCartController extends Controller
{
    public function semua() {
        try {
            $auth = Auth::guard('member')->user();
    
            $ordersToCancel = Order::where('id_peserta', $auth->id_peserta)
                ->where('status_order', 'pending')
                ->where('expiry_date', '<', now())
                ->get();
    
            foreach ($ordersToCancel as $order) {
                $order->update(['status_order' => 'cancel']);
    
                foreach ($order->details as $detail) {
                    $productable = $detail->productable;
    
                    $newStock = $productable->stock + $detail->quantity;
                    $productable->update(['stock' => $newStock]);
                }

                Notification::create([
                    'peserta_id' => $order->id_peserta,
                    'label' => 'Pesanan Anda Dibatalkan',
                    'description' => "Token pembayaran Anda expired. Pesanan dengan Nomor Order $order->no_order dibatalkan oleh sistem.",
                    'link' => route('shopping-cart.dibatalkan'),
                ]);
            }

            $trackingOrders = Order::where('id_peserta', $auth->id_peserta)
            ->where('status_aktif', 1)
            ->get();

            foreach ($trackingOrders as $order) {
                $tracking = Tracking::where('order_id', $order->order_id)->where('status', 'delivered')->first();
    
                if ($tracking && $tracking->status == 'delivered') {
                    $order->update(['status_order' => 'done']);

                    Notification::create([
                        'peserta_id' => $order->id_peserta,
                        'label' => 'Pesanan Anda Telah Sampai',
                        'description' => "Pesanan dengan Order ID $order->no_order telah sampai di tujuan. Konfirmasi kembali pesanan anda.",
                        'link' => route('shopping-cart.selesai'),
                    ]);
                }
            }

            $orderDones = Order::where('id_peserta', $auth->id_peserta)
                ->where('status_order', 'done')
                ->where('done', 0)
                ->where('status_aktif', 1)
                ->latest()
                ->get();

            $totalPoints = 0;

            foreach ($orderDones as $order) {
                $tracking = Tracking::where('order_id', $order->order_id)->first();

                if ($order->status_order == 'done') {
                    
                    $trackingDate = Carbon::parse($tracking->tanggal);
                    if ($trackingDate->diffInDays(now()) > 3) {

                        $order->update(['done' => 1]);

                        Notification::create([
                            'peserta_id' => $order->id_peserta,
                            'label' => 'Pesanan Anda Telah Sampai',
                            'description' => "Pesanan dengan Order ID $order->no_order telah selesai. Cek kembali barang pesanan Anda.",
                            'link' => route('shopping-cart.selesai'),
                        ]);

                        foreach ($order->details as $detail) {
                            $productable = $detail->productable;
            
                            $points = $productable->point * $detail->quantity;
                            $totalPoints += $points;
                        }
                    }
                }
            }

            $auth->coin += $totalPoints;
            $auth->save();
    
            $orders = Order::where('id_peserta', $auth->id_peserta)
                ->where('status_aktif', 1)
                ->latest()
                ->paginate(10);
    
            return view('new.pages.shopping-cart.semua', compact('orders'));
        } catch (\Exception $e) {
            return back()->withErrors('An error occurred while processing your request.');
        }
    }
    
    public function belumDibayar() {
        try {
            $auth = Auth::guard('member')->user();
    
            // Cancel orders that have expired
            $ordersToCancel = Order::where('id_peserta', $auth->id_peserta)
                ->where('status_order', 'pending')
                ->where('expiry_date', '<', now())
                ->get();
    
            foreach ($ordersToCancel as $order) {
                $order->update(['status_order' => 'cancel']);
    
                foreach ($order->details as $detail) {
                    $productable = $detail->productable;
    
                    $newStock = $productable->stock + $detail->quantity;
                    $productable->update(['stock' => $newStock]);
                }
            }
    
            // Fetch pending orders
            $orders = Order::where('id_peserta', $auth->id_peserta)
                ->where('status_order', 'pending')
                ->where('status_aktif', 1)
                ->latest()
                ->paginate(10);
    
            return view('new.pages.shopping-cart.semua', compact('orders'));
        } catch (\Exception $e) {
            // Return an error message without logging
            return back()->withErrors('An error occurred while processing your request.');
        }
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
