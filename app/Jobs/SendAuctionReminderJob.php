<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\SystemNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\SendWhatsAppBroadcast;

class SendAuctionReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now  = Carbon::now();
        $date = $now->toDateString();

        DB::transaction(function () use ($now, $date) {

            $alreadySent = SystemNotification::where('type', 'auction_reminder')
                ->whereDate('created_at', $date)
                ->exists();

            if ($alreadySent) {
                return;
            }

            $event = Event::lockForUpdate()
                ->whereDate('tgl_akhir', $date)
                ->orderBy('tgl_akhir', 'asc')
                ->first();

            if (!$event || !$event->tgl_akhir) {
                return;
            }

            $reminderTime = Carbon::parse($event->tgl_akhir)->subHour();
            // $reminderTime = Carbon::parse($event->tgl_akhir)->subMinutes(15);

            if ($now->lt($reminderTime)) {
                return;
            }

            SystemNotification::create([
                'type' => 'auction_reminder',
                'event_id' => $event->id_event,
                'label' => '⏰ Auction Akan Segera Berakhir',
                'description' => 'Hi, Onelito Koi Auction akan berakhir 1 jam ke depan, koi pilihan kamu jangan sampai terlewatkan 😊',
                'link' => route('auction.index'),
                'status' => 1,
            ]);

            $tanggalIndonesia = Carbon::parse($event->tgl_akhir)
                ->translatedFormat('d F Y');

            SendWhatsAppBroadcast::dispatch($tanggalIndonesia)
                ->onQueue('whatsapp');
        });
    }
}
