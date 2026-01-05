<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendOutbidWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 30;

    protected string $name;
    protected string $phone;

    public function __construct(string $name, string $phone)
    {
        $this->name = $name;
        $this->phone = $phone;
    }

    public function handle(): void
    {
        $url   = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
        $token = env('QONTAK_API_KEY');

        $payload = [
            "to_name" => $this->name,
            "to_number" => $this->phone,
            "message_template_id" => env('OUTBID_TEMPLATE_ID'),
            "channel_integration_id" => env('QONTAK_CHANNEL_INTEGRATION_ID'),
            "language" => [
                "code" => "id"
            ],
            "parameters" => [
                "header" => [
                    "format" => "DOCUMENT",
                    "params" => []
                ],
                "body" => [
                    [
                        "key" => "0",
                        "value_text" => $this->name,
                        "value" => "customer_name",
                    ]
                ],
                "buttons" => []
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        if (!$response->successful()) {
            throw new \Exception(
                'Outbid WhatsApp failed: ' . $response->body()
            );
        }
    }
}
