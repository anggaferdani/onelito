<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\EventFish;
use App\Models\Notification;
use App\Models\AuctionWinner;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendAuctionWinnerNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public function handle()
    {
        $product = EventFish::with('maxBid.member', 'event')->find($this->productId);
        if (!$product) return;

        $endTime = Carbon::parse($product->event->tgl_akhir);
        $addedExtraTime = $endTime->copy()->addMinutes($product->extra_time ?? 0);

        $lastBid = $product->maxBid;
        if ($lastBid && $lastBid->updated_at > $endTime) {
            $potentialEnd = Carbon::parse($lastBid->updated_at)->addMinutes($product->extra_time ?? 0);
            if ($potentialEnd > $addedExtraTime) {
                $addedExtraTime = $potentialEnd;
            }
        }

        if (now()->lt($addedExtraTime)) {
            self::dispatch($this->productId)->delay($addedExtraTime);
            return;
        }

        $winner = $product->maxBid?->member;
        if (!$winner) return;

        AuctionWinner::firstOrCreate(
            ['id_bidding' => $product->maxBid->id_bidding],
            ['create_by' => 1, 'update_by' => 1, 'status_aktif' => 1]
        );

        $fishVariety = "{$product->no_ikan} | {$product->variety} | {$product->breeder} | {$product->bloodline} | {$product->sex}";
        $finalBidPriceFormatted = number_format($product->maxBid->nominal_bid, 0, ',', '.');

        Notification::create([
            'peserta_id' => $winner->id_peserta,
            'label' => 'Auction Winner',
            'description' => "Selamat kepada {$winner->nama} telah memenangkan Lelang Koi {$fishVariety} dengan harga Rp {$finalBidPriceFormatted}",
            'link' => route('winning-auction'),
        ]);

        $this->sendWhatsApp($winner, $fishVariety, $finalBidPriceFormatted);
    }

    private function sendWhatsApp($winner, $fishVariety, $finalBidPriceFormatted)
    {
        $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
        $token = env('QONTAK_API_KEY');

        $phoneNumber = '62' . ltrim(preg_replace('/[^0-9]/', '', $winner->no_hp), '0');

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
