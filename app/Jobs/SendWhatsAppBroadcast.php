<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendWhatsAppBroadcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 30;

    protected string $tanggalIndonesia;

    public function __construct(string $tanggalIndonesia)
    {
        $this->tanggalIndonesia = $tanggalIndonesia;
    }

    public function handle(): void
    {
        $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp';
        $token = env('QONTAK_API_KEY');

        $payload = [
            'name' => "Reminder 1 Jam Sebelum Auction Berakhir Tanggal {$this->tanggalIndonesia}",
            'message_template_id' => env('MESSAGE_TEMPLATE_ID'),
            'contact_list_id' => env('CONTACT_LIST_ID'),
            'channel_integration_id' => env('QONTAK_CHANNEL_INTEGRATION_ID'),
            'parameters' => [
                'body' => []
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        if (!$response->successful()) {
            throw new \Exception('Failed to send WhatsApp broadcast');
        }
    }
}
