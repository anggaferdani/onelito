<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendAuctionReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function handle()
    {
        $event = Event::find($this->eventId);
        if (!$event) return;

        $eventDay = Carbon::parse($event->tgl_akhir)->toDateString();

        $alreadySent = Event::whereDate('tgl_akhir', $eventDay)
            ->where('notifikasi_dikirim', 0)
            ->exists();

        if ($alreadySent) {
            return;
        }

        $tanggalIndonesia = Carbon::parse($event->tgl_akhir)->translatedFormat('d F Y');

        $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp';
        $token = env('QONTAK_API_KEY');

        $data = [
            "name" => "Reminder 1 Jam Sebelum Auction Berakhir Tanggal {$tanggalIndonesia}",
            "message_template_id" => env('MESSAGE_TEMPLATE_ID'),
            "contact_list_id" => env('CONTACT_LIST_ID'),
            "channel_integration_id" => env('QONTAK_CHANNEL_INTEGRATION_ID'),
            "parameters" => [
                "body" => []
            ]
        ];

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($url, $data);

        Notification::create([
            'label' => 'Event Akan Segera Berakhir',
            'description' => "Hi, Onelito Koi Auction akan berakhir 1 jam ke depan, koi pilihan kamu jangan sampai terlewatkan 😊",
            'link' => route('auction.index'),
        ]);

        $event->notifikasi_dikirim = 0;
        $event->save();
    }
}
