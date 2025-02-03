<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendEventReminder extends Command
{
    protected $signature = 'event:send-reminder';
    protected $description = 'Mengirim notifikasi 1 jam sebelum event berakhir';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // $events = Event::where('status_aktif', 1)
        //                 ->where('tgl_akhir', '>', Carbon::now())
        //                 ->where('tgl_akhir', '<=', Carbon::now()->addHour())
        //                 ->where('notifikasi_dikirim', 1)
        //                 ->get();

        // foreach ($events as $event) {
        //     // if (Carbon::now()->diffInMinutes($event->tgl_akhir) >= 55 && Carbon::now()->diffInMinutes($event->tgl_akhir) <= 65) {
        //     if (Carbon::now()->diffInMinutes($event->tgl_akhir) >= 0 && Carbon::now()->diffInMinutes($event->tgl_akhir) <= 5) {
        //         Notification::create([
        //             'label' => 'Event Akan Segera Berakhir',
        //             'description' => "Hi, Onelito Koi Auction akan berakhir 1 jam ke depan, koi pilihan kamu jangan sampai terlewatkan 😊",
        //             'link' => route('auction.index'),
        //         ]);

        //         $event->update([
        //             'notifikasi_dikirim' => 0,
        //         ]);

        //         $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
        //         $token = env('QONTAK_API_KEY');

        //         $users = Member::where('status_aktif', 1)->orderBy('created_at', 'DESC')->get();

        //         foreach($users as $user) {
        //             $data = [
        //                 "to_name" => $user->nama,
        //                 "to_number" => $user->no_hp,
        //                 "message_template_id" => "0fd72c23-4703-4954-8fc3-fcdd548c8641",
        //                 "channel_integration_id" => env('QONTAK_CHANNEL_INTEGRATION_ID'),
        //                 "language" => [
        //                     "code" => "id",
        //                 ],
        //                 "parameters" => [
        //                     "header" => [
        //                         "format" => "DOCUMENT",
        //                         "params" => [],
        //                     ],
        //                     "body" => [
        //                         [
        //                             "key" => "0",
        //                             "value_text" => $user->nama,
        //                             "value" => "customer_name",
        //                         ]
        //                     ],
        //                     "buttons" => []
        //                 ]
        //             ];
    
        //             $response = Http::withHeaders([
        //                 'Authorization' => 'Bearer ' . $token,
        //                 'Content-Type' => 'application/json',
        //             ])->post($url, $data);
    
        //             if ($response->successful()) {
        //                 return response()->json([
        //                     'message' => 'Broadcast berhasil dikirim',
        //                     'data' => $response->json(),
        //                 ]);
        //             } else {
        //                 return response()->json([
        //                     'message' => $response->body(),
        //                     'error' => $response->body(),
        //                 ], $response->status());
        //             }
        //         }
        //     }
        // }

        // $this->info('Notifikasi event terkirim.');

        $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
        $token = env('QONTAK_API_KEY');

        $data = [
            "to_name" => 'Angga',
            "to_number" => '6281290573256',
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
                        "value_text" => 'Angga',
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
            $this->info("Broadcast berhasil dikirim");
        } else {
            $this->info("Gagal mengirim broadcast");
        }
    }
}
