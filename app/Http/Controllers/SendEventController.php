<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Order;
use App\Models\LogBid;
use App\Models\Member;
use App\Models\Tracking;
use App\Models\EventFish;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\WinnerNotificationLog;

class SendEventController extends Controller
{
    // public function sendEventReminder()
    // {
    //     // Update order statuses (tidak berubah dari kode asli)
    //     $trackingOrders = Order::where('status_order', 'delivered')->where('status_aktif', 1)
    //         ->get();

    //     foreach ($trackingOrders as $order) {
    //         $tracking = Tracking::where('order_id', $order->order_id)->where('status', 'delivered')->first();

    //         if ($tracking && $tracking->status == 'delivered') {
    //             $order->update(['status_order' => 'done']);
    //         }

    //     }

    //     // Ambil semua event yang memenuhi kriteria notifikasi_dikirim = 1
    //     $events = Event::where('status_aktif', 1)
    //         ->where('tgl_akhir', '>', Carbon::now())
    //         ->where('tgl_akhir', '<=', Carbon::now()->addHour())
    //         ->where('notifikasi_dikirim', 1)
    //         ->get(); // Menggunakan get() untuk mendapatkan semua event yang memenuhi syarat

    //     $notificationSent = false; // Flag untuk menandai apakah notifikasi sudah dikirim

    //     foreach ($events as $event) {
    //         if (Carbon::now()->diffInMinutes($event->tgl_akhir) >= 55 && Carbon::now()->diffInMinutes($event->tgl_akhir) <= 65 && !$notificationSent) {
    //             // Kirim notifikasi hanya jika belum dikirim
    //             Notification::create([
    //                 'label' => 'Event Akan Segera Berakhir',
    //                 'description' => "Hi, Onelito Koi Auction akan berakhir 1 jam ke depan, koi pilihan kamu jangan sampai terlewatkan 😊",
    //                 'link' => route('auction.index'),
    //             ]);

    //             $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
    //             $token = env('QONTAK_API_KEY');

    //             $users = Member::where('status_aktif', 1)->orderBy('created_at', 'DESC')->get();

    //             // Array untuk menampung response dari notifikasi
    //             $notificationResponses = [];

    //             foreach ($users as $user) {
    //                 $phoneNumber = $user->no_hp;
                        // $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
                        // if (preg_match('/^0/', $phoneNumber)) {
                        //     $phoneNumber = '62' . substr($phoneNumber, 1);
                        // }

    //                 $data = [
    //                     "to_name" => $user->nama,
    //                     "to_number" => $phoneNumber,
    //                     "message_template_id" => "0fd72c23-4703-4954-8fc3-fcdd548c8641",
    //                     "channel_integration_id" => env('QONTAK_CHANNEL_INTEGRATION_ID'),
    //                     "language" => [
    //                         "code" => "id",
    //                     ],
    //                     "parameters" => [
    //                         "header" => [
    //                             "format" => "DOCUMENT",
    //                             "params" => [],
    //                         ],
    //                         "body" => [
    //                             [
    //                                 "key" => "0",
    //                                 "value_text" => $user->nama,
    //                                 "value" => "customer_name",
    //                             ]
    //                         ],
    //                         "buttons" => []
    //                     ]
    //                 ];

    //                 $response = Http::withHeaders([
    //                     'Authorization' => 'Bearer ' . $token,
    //                     'Content-Type' => 'application/json',
    //                 ])->post($url, $data);


    //                 if ($response->successful()) {
    //                     $notificationResponses[] = [
    //                         'success' => true,
    //                         'message' => 'Broadcast berhasil dikirim ke: ' . $user->nama,
    //                         'data' => $response->json(),
    //                     ];
    //                 } else {
    //                     $notificationResponses[] = [
    //                         'success' => false,
    //                         'message' => 'Broadcast gagal dikirim ke: ' . $user->nama,
    //                         'error' => $response->body(),
    //                         'status' => $response->status(),
    //                     ];
    //                 }
    //             }

    //             $notificationSent = true; // Set flag menjadi true karena notifikasi sudah dikirim

    //             // Logika di sini hanya untuk keperluan contoh.
    //             // Dalam implementasi Anda, Anda mungkin tidak perlu mereturn response setelah setiap event.
    //             $response_data = [
    //                 'success' => true,
    //                 'message' => 'Reminder event berhasil dikirim',
    //                 'notification_responses' => $notificationResponses,
    //             ];
    //             $statusCode = 200;
    //         }
    //     }

    //     // Update notifikasi_dikirim menjadi 0 setelah selesai mengirim notifikasi untuk semua event yang memenuhi syarat
    //     if ($notificationSent) {
    //         foreach ($events as $event) {
    //             $event->update(['notifikasi_dikirim' => 0]);
    //         }
    //     }

    //     // Return response (jika ada)
    //     if (isset($response_data)) {
    //         return response()->json($response_data, $statusCode);
    //     } else {
    //         return response()->json(['message' => 'No events to notify'], 200);
    //     }
    // }

    // public function example() {
    //     $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
    //     $token = env('QONTAK_API_KEY');

    // $phoneNumber = $member->no_hp;
    // $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
    // if (preg_match('/^0/', $phoneNumber)) {
    //     $phoneNumber = '62' . substr($phoneNumber, 1);
    // }

    //     $data = [
    //         "to_name" => 'Angga',
    //         "to_number" => $phoneNumber,
    //         "message_template_id" => "0fd72c23-4703-4954-8fc3-fcdd548c8641",
    //         // "message_template_id" => "2c9c5f12-4578-4d36-9df9-b9296e9e9af2",
    //         // "message_template_id" => "421b85ad-6620-42b8-aafa-77cb8b50d654",
    //         "channel_integration_id" => env('QONTAK_CHANNEL_INTEGRATION_ID'),
    //         "language" => [
    //             "code" => "id",
    //         ],
    //         "parameters" => [
    //             "header" => [
    //                 "format" => "DOCUMENT",
    //                 "params" => [],
    //             ],
    //             "body" => [
    //                 [
    //                     "key" => "0",
    //                     "value_text" => 'Angga',
    //                     "value" => "customer_name",
    //                 ],
    //                 // [
    //                 //     "key" => "1",
    //                 //     "value_text" => 'Koi',
    //                 //     "value" => "fish_variety",
    //                 // ],
    //                 // [
    //                 //     "key" => "2",
    //                 //     "value_text" => '10.000',
    //                 //     "value" => "final_bid_price",
    //                 // ],
    //             ],
    //             "buttons" => []
    //         ]
    //     ];

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //         'Content-Type' => 'application/json',
    //     ])->post($url, $data);

    //     if ($response->successful()) {
    //         return response()->json([
    //             'message' => 'Broadcast berhasil dikirim',
    //             'data' => $response->json(),
    //         ]);
    //     } else {
    //         return response()->json([
    //             'message' => $response->body(),
    //             'error' => $response->body(),
    //         ], $response->status());
    //     }
    // }
}
