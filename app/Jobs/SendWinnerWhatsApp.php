<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendWinnerWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 30;

    protected string $name;
    protected string $phone;
    protected string $fishVariety;
    protected string $finalBid;

    public function __construct(
        string $name,
        string $phone,
        string $fishVariety,
        string $finalBid
    ) {
        $this->name = $name;
        $this->phone = $phone;
        $this->fishVariety = $fishVariety;
        $this->finalBid = $finalBid;
    }

    public function handle(): void
    {
        $url   = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
        $token = env('QONTAK_API_KEY');

        $payload = [
            "to_name" => $this->name,
            "to_number" => $this->phone,
            "message_template_id" => env('WINNER_TEMPLATE_ID'),
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
                    ],
                    [
                        "key" => "1",
                        "value_text" => $this->fishVariety,
                        "value" => "fish_variety",
                    ],
                    [
                        "key" => "2",
                        "value_text" => $this->finalBid,
                        "value" => "final_bid_price",
                    ],
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
                'Winner WhatsApp failed: ' . $response->body()
            );
        }
    }
}
