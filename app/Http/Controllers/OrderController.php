<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Alamat;
use App\Models\Member;
use App\Models\Tracking;
use Milon\Barcode\DNS1D;
use Xendit\Configuration;
use App\Mail\OrderRequest;
use App\Models\OrderDetail;
use App\Models\Notification;
use Illuminate\Http\Request;
use Xendit\Invoice\InvoiceApi;
use Barryvdh\DomPDF\Facade\Pdf;
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
   
    public function webhookOrderStatus(Request $request) {
        try {
            if ($request->isJson() && $request->getContent() === '') {
                return response()->json(['status' => 'ok'], 200);
            }

            $data = $request->all();

            Tracking::create([
                'order_id' => $data['order_id'],
                'order_price' => $data['order_price'],
                'courier_tracking_id' => $data['courier_tracking_id'],
                'courier_waybill_id' => $data['courier_waybill_id'],
                'courier_company' => $data['courier_company'],
                'courier_type' => $data['courier_type'],
                'courier_driver_name' => $data['courier_driver_name'],
                'courier_driver_phone' => $data['courier_driver_phone'],
                'courier_driver_plate_number' => $data['courier_driver_plate_number'],
                'courier_driver_photo_url' => $data['courier_driver_photo_url'],
                'courier_link' => $data['courier_link'],
                'tanggal' => now(),
                'status' => $data['status'],
            ]);

            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function orderSuccess(Request $request) {
        $order = Order::where('no_order', $request['external_id'])->where('status_aktif', 1)->first();

        return view('new.pages.order.success', compact(
            'order',
        ));
    }

    public function webhookOrderSuccess(Request $request) {
        try {
            $data = $request->all();

            $order = Order::where('no_order', $data['external_id'])->where('status_aktif', 1)->first();

            if ($order) {
                $order->update([
                    'status_order' => 'paid',
                    'paid_at' => now(),
                    'payment_method' => $data['payment_method'],
                    'payment_channel' => $data['payment_channel'],
                ]);

                $member = Member::where('id_peserta', $order->id_peserta)->first();

                if ($member && $order->coin_yang_digunakan > 0) {
                    $member->update([
                        'coin' => $member->coin - $order->coin_yang_digunakan,
                    ]);
                }

                Notification::create([
                    'peserta_id' => $order->id_peserta,
                    'label' => 'Pembayaran Berhasil',
                    'description' => "Pembayaran untuk pesanan dengan Nomor Order $order->no_order telah berhasil. Tim kami akan segera memproses pesanan Anda.",
                    'link' => route('shopping-cart.menunggu-konfirmasi'),
                ]);
            }

            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function order(Request $request) {
        try {
            $request->validate([
                'opsi_pengiriman' => 'required|in:otomatis,manual',
            ]);

            $auth = Auth::guard('member')->user();
            $now = Carbon::now();

            $shipper = Alamat::where('id', 1)->first();
            $destination = Alamat::where('id', $auth->pilih_alamat)->where('peserta_id', $auth->id_peserta)->first();

            $ids = explode(',', $request['ids']);
            $carts = Cart::where('id_peserta', $auth->id_peserta)
                        ->where('status_aktif', 1)
                        ->where('jumlah', '>', 0)
                        ->whereIn('id_keranjang', $ids)
                        ->get();

            foreach ($carts as $cart) {
                $cartable = $cart->cartable;

                if ($cartable->stock < $cart->jumlah) {
                    $errorMessage = $cartable->stock == 0
                        ? 'Stok untuk produk ' . $cartable->name . ' tidak mencukupi. Produk sudah habis.'
                        : 'Stok untuk produk ' . $cartable->name . ' tidak mencukupi. Stok tersisa hanya ' . $cartable->stock . '.';

                    return redirect()->route('cart')->withErrors(['error' => $errorMessage]);
                }
            }

            $array = [
                'no_order' => $now->format('Ymd'),
                'tanggal' => $now,
                'status' => '',
                'id_peserta' => $auth->id_peserta,
                'total_harga_barang' => $request['total_harga_barang'],
                'total_tagihan' => $request['total_tagihan'],
                'jumlah_total' => $request['jumlah_total'],
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
                'coin_yang_digunakan' => $request['coin_yang_digunakan'],
                'opsi_pengiriman' => $request['opsi_pengiriman'],
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
                        'total' => $cart->cartable->harga,
                        'name' => $cart->cartable->merek_produk . ', ' . $cart->cartable->nama_produk,
                        'description' => '',
                        'category' => '',
                        'value' => $cart->cartable->harga,
                        'quantity' => $cart->jumlah,
                        'weight' => $cart->cartable->weight * $cart->jumlah,
                        'height' => $cart->cartable->height,
                        'length' => $cart->cartable->length,
                        'width' => $cart->cartable->width,
                    ];
                } elseif ($cart->cartable_type === 'KoiStock') {
                    $detailOrder = [
                        'id_order' => $order->id_order,
                        'id_peserta' => $auth->id_peserta,
                        'productable_id' => $cart->cartable_id,
                        'productable_type' => $cart->cartable_type,
                        'jumlah_produk' => $cart->jumlah,
                        'total' => $cart->cartable->harga_ikan,
                        'name' => $cart->cartable->variety . ', ' . $cart->cartable->breeder . ', ' . $cart->cartable->bloodline . ', ' . $cart->cartable->size,
                        'description' => '',
                        'category' => '',
                        'value' => $cart->cartable->harga_ikan,
                        'quantity' => 1,
                        'height' => 3,
                        'weight' => 10,
                        'length' => 10,
                        'width' => 10,
                    ];
                }

                OrderDetail::create($detailOrder);
            }

            $items = [];
            foreach ($carts as $cart) {
                $items[] = new InvoiceItem([
                    'name' => $cart->cartable_type === 'KoiStock' ? $cart->cartable->variety . ', ' . $cart->cartable->breeder . ', ' . $cart->cartable->bloodline . ', ' . $cart->cartable->size : $cart->cartable->merek_produk . ', ' . $cart->cartable->nama_produk,
                    'price' => $cart->cartable_type === 'KoiStock' ? $cart->cartable->harga_ikan : $cart->cartable->harga,
                    'quantity' => $cart->jumlah,
                ]);
            }

            if ($request['opsi_pengiriman'] === 'otomatis') {
                $fees = [
                    [
                        'type' => 'Ongkos Kirim',
                        'value' => $request['ongkos_kirim'],
                    ]
                ];
            } else {
                $fees = [];
            }

            if ($request['total_tagihan'] > 0) {
                $createInvoice = new CreateInvoiceRequest([
                    'external_id' => (string) $order->no_order,
                    'amount' => (int) $request->input('total_tagihan'),
                    'items' => $items,
                    'fees' => $fees,
                    'success_redirect_url' => route('order.success', ['external_id' => $order->no_order]),
                    'failure_redirect_url' => url()->previous(),
                ]);

                $apiInstance = new InvoiceApi();
                $generateInvoice = $apiInstance->createInvoice($createInvoice);
                $order->update([
                    'invoice_url' => $generateInvoice['invoice_url'],
                    'expiry_date' => $generateInvoice['expiry_date'],
                ]);

                Notification::create([
                    'peserta_id' => $order->id_peserta,
                    'label' => 'Order Berhasil! Segera Selesaikan Pembayaran Anda',
                    'description' => "Pesanan Anda dengan Nomor Order $order->no_order telah berhasil dibuat. Pastikan untuk menyelesaikan pembayaran sebelum tanggal " . $order->expiry_date->format('Y-m-d H:i:s') . ". Anda bisa memeriksa detail pesanan Anda kapan saja.",
                    'link' => route('shopping-cart.belum-dibayar'),
                ]);

                return redirect()->route('shopping-cart.belum-dibayar');
            } else {
                $order->update([
                    'status_order' => 'paid',
                    'paid_at' => now(),
                    'payment_method' => 'Onelito Coins',
                    'payment_channel' => 'Onelito Coins',
                ]);

                $member = Member::where('id_peserta', $order->id_peserta)->first();

                if ($member && $order->coin_yang_digunakan > 0) {
                    $member->update([
                        'coin' => $member->coin - $order->coin_yang_digunakan,
                    ]);
                }

                Notification::create([
                    'peserta_id' => $order->id_peserta,
                    'label' => 'Order Berhasil!',
                    'description' => "Pesanan Anda dengan Nomor Order $order->no_order telah berhasil dibuat. Anda dapat memeriksa detail pesanan Anda kapan saja.",
                    'link' => route('shopping-cart.menunggu-konfirmasi'),
                ]);

                return redirect()->route('order.success', ['external_id' => $order->no_order]);
            }

            foreach ($order->details as $detail) {
                $productable = $detail->productable;
                $newStock = $productable->stock - $detail->quantity;
                $productable->update(['stock' => $newStock]);
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function orderInvoice($no_order) {
        $order = Order::where('no_order', $no_order)->first();

        return view('new.pages.order.invoice', compact(
            'order',
        ));
    }

    public function orderResi($no_order)
    {
        $order = Order::where('no_order', $no_order)->first();

        if (!$order) {
            abort(404, 'Order not found');
        }

        $totalQuantity = 0;
        $totalWeight = 0;
        foreach ($order->details as $detail) {
            $totalQuantity += $detail->quantity;
            $weightInKilograms = $detail->weight / 1000;
            $totalWeight += $weightInKilograms * $detail->quantity;
        }
        $totalWeight = number_format($totalWeight, 2);

        $dns1d = new DNS1D();

        $waybillBarcode = $dns1d->getBarcodeHTML($order->waybill_id, 'C128', 1.5, 30);

        $referenceNumberBarcode = $dns1d->getBarcodeHTML($order->order_id, 'C128', 1.5, 30);

        $logoPath = public_path('img/logo-onelito.png');

        if (file_exists($logoPath)) {
            $logoBase64 = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoBase64;
        } else {
            $logoSrc = null;
        }

        $data = [
            'order' => $order,
            'totalQuantity' => $totalQuantity,
            'totalWeight' => $totalWeight,
            'waybillBarcode' => $waybillBarcode,
            'waybillId' => $order->waybill_id,
            'referenceNumberBarcode' => $referenceNumberBarcode,
            'orderId' => $order->order_id,
            'logoSrc' => $logoSrc,
        ];

        $waybillIdChunks = str_split($order->waybill_id, 4);
        $data['waybillIdFormatted'] = implode(' ', $waybillIdChunks);

        $orderIdChunks = str_split($order->order_id, 4);
        $data['orderIdFormatted'] = implode(' ', $orderIdChunks);

        $html = view('new.pages.order.resi', $data)->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('order_label_' . $order->no_order . '.pdf');
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
    
    public function done($no_order) {
        $order = Order::where('no_order', $no_order)->where('status_aktif', 1)->first();

        try {
            if ($order) {
                $order->update([
                    'status_order' => 'done',
                ]);

                Notification::create([
                    'peserta_id' => $order->id_peserta,
                    'label' => 'Pesanan Anda Telah Selesai Diproses.',
                    'description' => "Pesanan dengan Nomor Order $order->no_order telah selesai diproses.",
                    'link' => route('shopping-cart.dikirim'),
                ]);
    
                return back()->with('success', 'Order dengan No Order: ' . $no_order . ' telah selesai.');
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
                    'length' => $orderDetail->length,
                    'weight' => $orderDetail->weight,
                    'width' => $orderDetail->width,
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

            Notification::create([
                'peserta_id' => $order->id_peserta,
                'label' => 'Pesanan Anda Sedang Diproses',
                'description' => "Pesanan Anda dengan Nomor Order $order->no_order telah berhasil dikirim. Harap cek secara berkala untuk memastikan pesanan Anda sampai dengan aman.",
                'link' => route('shopping-cart.dikirim'),
            ]);
            
            return back()->with('success', 'Order dengan No Order: ' . $no_order . ' berhasil dikirim.');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function selesai($no_order) {
        $auth = Auth::guard('member')->user();
        $order = Order::where('no_order', $no_order)->where('status_order', 'done')->where('status_aktif', 1)->first();

        try {
            if ($order) {
                $order->update([
                    'done' => 1,
                ]);

                $totalPoints = 0;

                foreach ($order->details as $detail) {
                    $productable = $detail->productable;

                    $points = $productable->point * $detail->quantity;
                    $totalPoints += $points;
                }

                $auth->coin += $totalPoints;
                $auth->save();

                Notification::create([
                    'peserta_id' => $order->id_peserta,
                    'label' => 'Pesanan Anda Telah Sampai',
                    'description' => "Pesanan dengan Order ID $order->no_order telah selesai. Cek kembali barang pesanan Anda.",
                    'link' => route('shopping-cart.selesai'),
                ]);
    
                return back()->with('success', 'Order dengan No Order: ' . $no_order . ' selesai.');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function cancel(Request $request, $no_order) {
        $request->validate([
            'alasan_membatalkan_pesanan' => 'required',
        ]);

        $order = Order::where('no_order', $no_order)->where('status_aktif', 1)->first();

        try {
            if ($order) {
                $order->update([
                    'status_order' => 'cancel',
                    'alasan_membatalkan_pesanan' => $request['alasan_membatalkan_pesanan'],
                    'dibatalkan_pembeli' => 1,
                ]);

                foreach ($order->details as $detail) {
                    $productable = $detail->productable;
    
                    $newStock = $productable->stock + $detail->quantity;
                    $productable->update(['stock' => $newStock]);
                }

                Notification::create([
                    'peserta_id' => $order->id_peserta,
                    'label' => 'Pesanan Anda Dibatalkan',
                    'description' => "Pesanan dengan Nomor Order $order->no_order dibatalkan.",
                    'link' => route('shopping-cart.dibatalkan'),
                ]);
    
                return redirect()->route('shopping-cart.dibatalkan')->with('success', 'Order dengan No Order: ' . $no_order . ' dibatalkan.');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function cancelByAdmin(Request $request, $no_order) {
        $request->validate([
            'alasan_membatalkan_pesanan' => 'required',
        ]);

        $order = Order::where('no_order', $no_order)->where('status_aktif', 1)->first();

        try {
            if ($order) {
                $order->update([
                    'status_order' => 'cancel',
                    'alasan_membatalkan_pesanan' => $request['alasan_membatalkan_pesanan'],
                    'dibatalkan_pembeli' => 2,
                ]);

                foreach ($order->details as $detail) {
                    $productable = $detail->productable;
    
                    $newStock = $productable->stock + $detail->quantity;
                    $productable->update(['stock' => $newStock]);
                }

                Notification::create([
                    'peserta_id' => $order->id_peserta,
                    'label' => 'Pesanan Anda Dibatalkan oleh Admin',
                    'description' => "Pesanan dengan Nomor Order $order->no_order dibatalkan oleh Admin dengan konfirmasi pengguna.",
                    'link' => route('shopping-cart.dibatalkan'),
                ]);
    
                return back()->with('success', 'Order dengan No Order: ' . $no_order . ' dibatalkan.');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
