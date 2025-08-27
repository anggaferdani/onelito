<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\Notification;
use App\Models\AuctionWinner;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;

class SendAuctionWinnerNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function handle()
    {
        $event = Event::with('auctionProducts.maxBid.member')->find($this->eventId);
        if (!$event) return;

        foreach ($event->auctionProducts as $product) {
            $winner = $product->maxBid?->member;
            if (!$winner) continue;

            $fishVariety = "{$product->no_ikan} | {$product->variety} | {$product->breeder} | {$product->bloodline} | {$product->sex}";
            $finalBidPrice = $product->maxBid->nominal_bid;
            $finalBidPriceFormatted = number_format($finalBidPrice, 0, ',', '.');

            AuctionWinner::firstOrCreate([
                'id_bidding' => $product->maxBid->id_bidding,
            ], [
                'create_by' => 1,
                'update_by' => 1,
                'status_aktif' => 1,
            ]);

            Notification::create([
                'peserta_id' => $winner->id_peserta,
                'label' => 'Auction Winner',
                'description' => "Selamat kepada {$winner->nama} telah memenangkan Lelang Koi {$fishVariety} dengan harga Rp {$finalBidPriceFormatted}",
                'link' => route('winning-auction'),
            ]);

            $this->sendWhatsApp($winner, $fishVariety, $finalBidPriceFormatted);
        }
    }

    private function sendWhatsApp($winner, $fishVariety, $finalBidPriceFormatted)
    {
        $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
        $token = env('QONTAK_API_KEY');

        $phoneNumber = preg_replace('/[^0-9]/', '', $winner->no_hp);
        if (preg_match('/^0/', $phoneNumber)) {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        }

        $data = [
            "to_name" => $winner->nama,
            "to_number" => $phoneNumber,
            "message_template_id" => env('WINNER_TEMPLATE_ID'),
            "channel_integration_id" => env('QONTAK_CHANNEL_INTEGRATION_ID'),
            "language" => ["code" => "id"],
            "parameters" => [
                "body" => [
                    ["key" => "0", "value_text" => $winner->nama, "value" => "customer_name"],
                    ["key" => "1", "value_text" => $fishVariety, "value" => "fish_variety"],
                    ["key" => "2", "value_text" => $finalBidPriceFormatted, "value" => "final_bid_price"],
                ]
            ]
        ];

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($url, $data);
    }
}
