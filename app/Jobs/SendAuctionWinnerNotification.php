<?php

namespace App\Jobs;

use App\Models\LogBid;
use App\Models\EventFish;
use App\Models\AuctionWinner;
use App\Models\Notification;
use App\Services\AuctionTimeService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\SendWinnerWhatsApp;

class SendAuctionWinnerNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $eventFishId;

    public function __construct(int $eventFishId)
    {
        $this->eventFishId = $eventFishId;
    }

    public function handle(): void
    {
        DB::transaction(function () {

            $fish = EventFish::lockForUpdate()->find($this->eventFishId);
            if (!$fish) {
                return;
            }

            if (!AuctionTimeService::isFishEnded($fish)) {
                return;
            }

            if ($fish->auction_status !== 'open') {
                return;
            }

            $topBid = LogBid::with('member')
                ->where('id_ikan_lelang', $fish->id_ikan)
                ->orderByDesc('nominal_bid')
                ->orderBy('waktu_bid')
                ->first();

            if (!$topBid) {
                $fish->update([
                    'auction_status' => 'no_bid',
                ]);
                return;
            }

            $existingWinner = AuctionWinner::where('id_ikan_lelang', $fish->id_ikan)
                ->lockForUpdate()
                ->first();

            if ($existingWinner) {
                $fish->update([
                    'auction_status' => 'won',
                ]);
                return;
            }

            AuctionWinner::create([
                'id_ikan_lelang' => $fish->id_ikan,
                'id_bidding' => $topBid->id_bidding,
                'nominal' => $topBid->nominal_bid,
                'status_aktif' => 1,
                'create_by' => 1,
            ]);

            $fish->update([
                'auction_status' => 'won',
            ]);

            $member = $topBid->member;

            if (!$member || empty($member->no_hp)) {
                return;
            }

            $fishVariety = "{$fish->no_ikan} | {$fish->variety} | {$fish->breeder} | {$fish->bloodline} | {$fish->sex}";
            $finalBidPriceFormatted = number_format($topBid->nominal_bid, 0, ',', '.');

            Notification::create([
                'peserta_id' => $topBid->id_peserta,
                'label' => 'Auction Winner',
                'description' => "Selamat kepada {$topBid->member->nama} telah memenangkan Lelang Koi {$fishVariety} dengan harga Rp {$finalBidPriceFormatted}",
                'link' => route('winning-auction'),
                'status' => 1,
            ]);

            $phone = $this->normalizePhone($member->no_hp);

            SendWinnerWhatsApp::dispatch(
                $member->nama,
                $phone,
                $fishVariety,
                $finalBidPriceFormatted
            )->onQueue('whatsapp');
        });
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '62')) {
            return $phone;
        }

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        return '62' . $phone;
    }
}
