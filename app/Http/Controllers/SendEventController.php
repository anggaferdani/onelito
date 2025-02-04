<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Member;
use App\Models\Order;
use App\Models\Tracking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendEventController extends Controller
{
    public function sendEventReminder()
    {
        $trackingOrders = Order::where('status_order', 'delivered')->where('status_aktif', 1)
            ->get();

        foreach ($trackingOrders as $order) {
            $tracking = Tracking::where('order_id', $order->order_id)->where('status', 'delivered')->first();

            if ($tracking && $tracking->status == 'delivered') {
                $order->update(['status_order' => 'done']);
            }

        }
            
        // Ambil semua event yang memenuhi kriteria notifikasi_dikirim = 1
        $events = Event::where('status_aktif', 1)
            ->where('tgl_akhir', '>', Carbon::now())
            ->where('tgl_akhir', '<=', Carbon::now()->addHour())
            ->where('notifikasi_dikirim', 1)
            ->get();


        foreach ($events as $event) {
            if (Carbon::now()->diffInMinutes($event->tgl_akhir) >= 0 && Carbon::now()->diffInMinutes($event->tgl_akhir) <= 5) {
                Notification::create([
                    'label' => 'Event Akan Segera Berakhir',
                    'description' => "Hi, Onelito Koi Auction akan berakhir 1 jam ke depan, koi pilihan kamu jangan sampai terlewatkan 😊",
                    'link' => route('auction.index'),
                ]);

                $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
                $token = env('QONTAK_API_KEY');

                $users = Member::where('status_aktif', 1)->orderBy('created_at', 'DESC')->get();

                // Array untuk menampung response dari notifikasi
                $notificationResponses = [];

                foreach ($users as $user) {
                    $phoneNumber = $user->no_hp;
                    $phoneNumber = preg_replace('/\D/', '', $phoneNumber);
                    if (strpos($phoneNumber, '0') === 0) {
                        $phoneNumber = '62' . substr($phoneNumber, 1);
                    } else if (strpos($phoneNumber, '62') !== 0) {
                        $phoneNumber = '62' . $phoneNumber;
                    }

                    $data = [
                        "to_name" => $user->nama,
                        "to_number" => $phoneNumber,
                        "message_template_id" => "0fd72c23-4703-4954-8fc3-fcdd548c8641",
                        "channel_integration_id" => env('QONTAK_CHANNEL_INTEGRATION_ID'),
                        "language" => [
                            "code" => "id",
                        ],
                        "parameters" => [
                            "header" => [
                                "format" => "DOCUMENT",
                                "params" => [],
                            ],
                            "body" => [
                                [
                                    "key" => "0",
                                    "value_text" => $user->nama,
                                    "value" => "customer_name",
                                ]
                            ],
                            "buttons" => []
                        ]
                    ];

                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json',
                    ])->post($url, $data);


                    if ($response->successful()) {
                        $notificationResponses[] = [
                            'success' => true,
                            'message' => 'Broadcast berhasil dikirim ke: ' . $user->nama,
                            'data' => $response->json(),
                        ];
                    } else {
                        $notificationResponses[] = [
                            'success' => false,
                            'message' => 'Broadcast gagal dikirim ke: ' . $user->nama,
                            'error' => $response->body(),
                            'status' => $response->status(),
                        ];
                    }
                }
                // Update notifikasi_dikirim menjadi 0 setelah selesai mengirim notifikasi untuk semua member di event ini
                $event->update([
                    'notifikasi_dikirim' => 0,
                ]);
                
                // Logika di sini hanya untuk keperluan contoh. 
                // Dalam implementasi Anda, Anda mungkin tidak perlu mereturn response setelah setiap event.
                return response()->json([
                        'success' => true,
                        'message' => 'Reminder event berhasil dikirim',
                    'notification_responses' => $notificationResponses,
                    ], 200);
            }
        }
    }

    public function example() {
        $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
        $token = env('QONTAK_API_KEY');

        $phoneNumber = "081290573256";
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);
        if (strpos($phoneNumber, '0') === 0) {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        } else if(strpos($phoneNumber, '62') !== 0){
                $phoneNumber = '62' . $phoneNumber;
        }

        $data = [
            "to_name" => 'Angga',
            "to_number" => $phoneNumber,
            "message_template_id" => "0fd72c23-4703-4954-8fc3-fcdd548c8641",
            // "message_template_id" => "2c9c5f12-4578-4d36-9df9-b9296e9e9af2",
            // "message_template_id" => "421b85ad-6620-42b8-aafa-77cb8b50d654",
            "channel_integration_id" => env('QONTAK_CHANNEL_INTEGRATION_ID'),
            "language" => [
                "code" => "id",
            ],
            "parameters" => [
                "header" => [
                    "format" => "DOCUMENT",
                    "params" => [],
                ],
                "body" => [
                    [
                        "key" => "0",
                        "value_text" => 'Angga',
                        "value" => "customer_name",
                    ],
                    // [
                    //     "key" => "1",
                    //     "value_text" => 'Koi',
                    //     "value" => "fish_variety",
                    // ],
                    // [
                    //     "key" => "2",
                    //     "value_text" => '10.000',
                    //     "value" => "final_bid_price",
                    // ],
                ],
                "buttons" => []
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($url, $data);

        if ($response->successful()) {
            return response()->json([
                'message' => 'Broadcast berhasil dikirim',
                'data' => $response->json(),
            ]);
        } else {
            return response()->json([
                'message' => $response->body(),
                'error' => $response->body(),
            ], $response->status());
        }
    }
}
