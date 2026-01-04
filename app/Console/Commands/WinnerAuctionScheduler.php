<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EventFish;
use App\Jobs\SendAuctionWinnerNotification;
use App\Services\AuctionTimeService;

class WinnerAuctionScheduler extends Command
{
    protected $signature = 'auction:process-winner';

    protected $description = 'Process auction winner per fish after auction + extra time ended';

    public function handle()
    {
        $fishes = EventFish::with('event')
            ->where('status_aktif', 1)
            ->where('auction_status', 'open')
            ->whereHas('event', function ($q) {
                $q->where('status_tutup', 0);
            })
            ->get();

        foreach ($fishes as $fish) {

            if (!AuctionTimeService::isFishEnded($fish)) {
                continue;
            }

            SendAuctionWinnerNotification::dispatch(
                $fish->id_ikan
            )->onQueue('auction-winner');
        }

        return Command::SUCCESS;
    }
}
