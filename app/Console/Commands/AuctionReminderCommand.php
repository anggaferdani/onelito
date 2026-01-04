<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendAuctionReminderJob;

class AuctionReminderCommand extends Command
{
    protected $signature = 'auction:send-reminder';
    protected $description = 'Send auction reminder 1 hour before end.';

    public function handle()
    {
        SendAuctionReminderJob::dispatch();
        return Command::SUCCESS;
    }
}
