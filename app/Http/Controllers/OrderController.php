<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Alamat;
use Xendit\Configuration;
use App\Mail\OrderRequest;
use App\Models\OrderDetail;
use App\Models\Notification;
use Illuminate\Http\Request;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\InvoiceItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Xendit\Invoice\CreateInvoiceRequest;

class OrderController extends Controller
{
    public function __construct()
   {
       Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
   }

    public function order(Request $request) {
        $request->validate([
        ]);

        $auth = Auth::guard('member')->user();
        $now = Carbon::now();

        $shipper = Alamat::where('id', 1)->first();
        $destination = Alamat::where('id', $auth->pilih_alamat)->where('peserta_id', $auth->id_peserta)->first();

        $ids = explode(',', $request['ids']);
        $carts = Cart::where('id_peserta', $auth->id_peserta)->where('status_aktif', 1)->where('jumlah', '>', 0)->whereIn('id_keranjang', $ids)->get();

        try {
            $array = [
                'no_order' => $now->format('Ymd'),
                'tanggal' => $now,
                'status' => '',
                'id_peserta' => $auth->id_peserta,
                'total_harga_barang' => $request['total_harga_barang'],
                'total_tagihan' => $request['total_tagihan'],
                'shipper_contact_name' => $shipper->nama,
                'shipper_contact_phone' => $shipper->no_hp,
                'shipper_contact_email' => $shipper->email,
                'shipper_organization' => $shipper->organisasi,
                'origin_contact_name' => $shipper->nama,
                'origin_contact_phone' => $shipper->no_hp,
                'origin_address' => $shipper->alamat_lengkap,
                'origin_postal_code' => $shipper->kode_pos,
                'origin_note' => $shipper->catatan,
                'origin_coordinate_latitude' => $shipper->latitude,
                'origin_coordinate_longitude' => $shipper->longitude,
                'destination_contact_name' => $destination->nama,
                'destination_contact_phone' => $destination->no_hp,
                'destination_contact_email' => $destination->email,
                'destination_address' => $destination->alamat_lengkap,
                'destination_postal_code' => $destination->kode_pos,
                'destination_note' => $destination->catatan,
                'destination_coordinate_latitude' => $destination->latitude,
                'destination_coordinate_longitude' => $destination->longitude,
                'courier_name' => $request['courier_name'],
                'courier_company' => $request['courier_code'],
                'courier_type' => $request['courier_type'],
                'courier_service_name' => $request['courier_service_name'],
                'courier_price' => $request['ongkos_kirim'],
                'delivery_type' => 'now',
                'order_note' => null,
                'status_order' => 'pending',
            ];

            $order = Order::create($array);

            $order->update([
                'no_order' => $order->no_order.$order->id_order,
            ]);

            foreach ($carts as $cart) {
                if ($cart->cartable_type === 'Product') {
                    $detailOrder = [
                        'id_order' => $order->id_order,
                        'id_peserta' => $auth->id_peserta,
                        'productable_id' => $cart->cartable_id,
                        'productable_type' => $cart->cartable_type,
                        'jumlah_produk' => $cart->jumlah,
                        'total' => $cart->cartable_type === 'KoiStock' ? $cart->cartable->harga_ikan : $cart->cartable->harga,
                        'name' => $cart->cartable->merek_produk . ', ' . $cart->cartable->nama_produk,
                        'description' => '',
                        'category' => '',
                        'value' => $cart->cartable->harga,
                        'quantity' => $cart->jumlah,
                        'weight' => $cart->cartable->weight * $cart->jumlah,
                        'height' => '',
                        'length' => '',
                        'width' => '',
                    ];
                } elseif ($cart->cartable_type === 'KoiStock') {
                    $detailOrder = [
                        'id_order' => $order->id_order,
                        'id_peserta' => $auth->id_peserta,
                        'productable_id' => $cart->cartable_id,
                        'productable_type' => $cart->cartable_type,
                        'jumlah_produk' => $cart->jumlah,
                        'total' => $cart->cartable_type === 'KoiStock' ? $cart->cartable->harga_ikan : $cart->cartable->harga,
                        'name' => $cart->cartable->variety . ', ' . $cart->cartable->breeder . ', ' . $cart->cartable->bloodline . ', ' . $cart->cartable->size,
                        'description' => '',
                        'category' => '',
                        'value' => $cart->cartable->harga_ikan,
                        'quantity' => $cart->jumlah,
                        'height' => 10,
                        'weight' => 1000,
                        'length' => 10,
                        'width' => 10,
                    ];
                }

                OrderDetail::create($detailOrder);

                $items[] = new InvoiceItem([
                    'name' => $cart->cartable_type === 'KoiStock' ? $cart->cartable->variety . ', ' . $cart->cartable->breeder . ', ' . $cart->cartable->bloodline . ', ' . $cart->cartable->size : $cart->cartable->merek_produk . ', ' . $cart->cartable->nama_produk,
                    'price' => $cart->cartable_type === 'KoiStock' ? $cart->cartable->harga_ikan : $cart->cartable->harga,
                    'quantity' => $cart->jumlah,
                ]);
            }

            $fees = [
                [
                    'type' => 'Ongkos Kirim',
                    'value' => $request['ongkos_kirim'],
                ]
            ];

            $createInvoice = new CreateInvoiceRequest([
                'external_id' => (string) $order->no_order,
                'amount' => (int) $request->input('total_tagihan'),
                'items' => $items,
                'fees' => $fees,
                'success_redirect_url' => route('order.success', ['external_id' => $order->no_order, 'status' => 'PAID', 'payment_method' => 'BANK_TRANSFER', 'payment_channel' => 'EWALLET']),
                // 'success_redirect_url' => route('order.success'),
                'failure_redirect_url' => url()->previous(),
            ]);

            
            $apiInstance = new InvoiceApi();
            $generateInvoice = $apiInstance->createInvoice($createInvoice);
            $order->update([
                'invoice_url' => $generateInvoice['invoice_url'],
                'expiry_date' => $generateInvoice['expiry_date'],
            ]);
            // dd($generateInvoice);

            // return redirect($order->invoice_url);
            return redirect()->route('shopping-cart.belum-dibayar');

        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function orderSuccess(Request $request) {
        $data = $request->all();

        $order = Order::where('no_order', $data['external_id'])->where('status_aktif', 1)->first();

        if ($order) {
            $order->update([
                'status_order' => strtolower($data['status']),
                // 'paid_at' => $data['paid_at'],
                'paid_date' => now(),
                'payment_method' => $data['payment_method'],
                'payment_channel' => $data['payment_channel'],
            ]);

            Notification::create([
                'peserta_id' => $order->id_peserta,
                'label' => 'Pesanan Anda Sedang Diproses',
                'description' => "Pesanan dengan Nomor Order $order->no_order sedang dalam proses. Tim kami segera menyiapkan dan mengirimkan pesananmu.",
                'link' => route('shopping-cart.sedang-diproses'),
            ]);
        }

        // return response()->json([
        //     'status' => 'success',
        // ], 200);

        return view('new.pages.order.success', compact(
            'order',
        ));
    }

    public function orderInvoice($no_order) {
        $order = Order::where('no_order', $no_order)->first();

        return view('new.pages.order.invoice', compact(
            'order',
        ));
    }

    public function process($no_order) {
        $order = Order::where('no_order', $no_order)->where('status_aktif', 1)->first();

        try {
            if ($order) {
                $order->update([
                    'status_order' => 'process',
                ]);

                Notification::create([
                    'peserta_id' => $order->id_peserta,
                    'label' => 'Pesanan Anda Sedang Diproses',
                    'description' => "Pesanan dengan Nomor Order $order->no_order sedang dalam proses. Tim kami segera menyiapkan dan mengirimkan pesananmu.",
                    'link' => route('shopping-cart.sedang-diproses'),
                ]);
    
                return back()->with('success', 'Order dengan No Order: ' . $no_order . ' sedang diproses.');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function kirim($no_order) {
        $order = Order::where('no_order', $no_order)->where('status_aktif', 1)->first();

        $orderDetails = OrderDetail::where('id_order', $order->id_order)->where('status_aktif', 1)->get();

        try {
            $orderRequest = [
                'shipper_contact_name' => $order->shipper_contact_name,
                'shipper_contact_phone' => $order->shipper_contact_phone,
                'shipper_contact_email' => $order->shipper_contact_email,
                'shipper_organization' => $order->shipper_organization,
                'origin_contact_name' => $order->origin_contact_name,
                'origin_contact_phone' => $order->origin_contact_phone,
                'origin_address' => $order->origin_address,
                'origin_note' => $order->origin_note,
                'origin_postal_code' => $order->origin_postal_code,
                'origin_coordinate' => [
                    'latitude' => $order->origin_coordinate_latitude,
                    'longitude' => $order->origin_coordinate_longitude,
                ],
                'destination_contact_name' => $order->destination_contact_name,
                'destination_contact_phone' => $order->destination_contact_phone,
                'destination_contact_email' => $order->destination_contact_email,
                'destination_address' => $order->destination_address,
                'destination_postal_code' => $order->destination_postal_code,
                'destination_note' => $order->destination_note,
                'destination_coordinate' => [
                    'latitude' => $order->destination_coordinate_latitude,
                    'longitude' => $order->destination_coordinate_longitude,
                ],
                'courier_company' => $order->courier_company,
                'courier_type' => $order->courier_type,
                'delivery_type' => $order->delivery_type,
                'order_note' => $order->order_note,
                'items' => [],
            ];

            foreach ($orderDetails as $orderDetail) {
                $orderRequest['items'][] = [
                    'name' => $orderDetail->name,
                    'description' => '',
                    'category' => '',
                    'value' => $orderDetail->value,
                    'quantity' => $orderDetail->quantity,
                    'height' => $orderDetail->height,
                    'length' => '',
                    'weight' => '',
                    'width' => '',
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('BITESHIP_API_KEY'),
                'Content-Type' => 'application/json'
            ])->post('https://api.biteship.com/v1/orders', $orderRequest);
            
            if ($response->failed()) {
                dd($response->json());
            }
            
            $order->update([
                'status_order' => 'delivered',
                'order_id' => $response->json('id'),
                'reference_id' => $response->json('reference_id'),
                'tracking_id' => $response->json('courier.tracking_id'),
                'waybill_id' => $response->json('courier.waybill_id'),
                'tracking_url' => $response->json('courier.link'),
            ]);
            
            return back()->with('success', 'Order dengan No Order: ' . $no_order . ' berhasil dikirim.');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function selesai($no_order) {
        $order = Order::where('no_order', $no_order)->where('status_aktif', 1)->first();

        try {
            if ($order) {
                $order->update([
                    'status_order' => 'done',
                ]);
    
                return back()->with('success', 'Order dengan No Order: ' . $no_order . ' selesai.');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function cancel(Request $request, $no_order) {
        $order = Order::where('no_order', $no_order)->where('status_aktif', 1)->first();

        try {
            if ($order) {
                $order->update([
                    'status_order' => 'cancel',
                    'alasan_membatalkan_pesanan' => $request['alasan_membatalkan_pesanan'],
                ]);
    
                return redirect()->route('shopping-cart.dibatalkan')->with('success', 'Order dengan No Order: ' . $no_order . ' dibatalkan.');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
