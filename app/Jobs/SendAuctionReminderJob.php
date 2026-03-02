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

            $event = Event::lockForUpdate()
                ->whereDate('tgl_akhir', $date)
                ->where('status_aktif', 1)
                ->where('status_tutup', 0)
                ->where('reminder_sent', 0)
                ->orderBy('tgl_akhir', 'asc')
                ->first();

            if (!$event || !$event->tgl_akhir) {
                return;
            }

            // $reminderTime = Carbon::parse($event->tgl_akhir)->subMinutes(15);
            $reminderTime = Carbon::parse($event->tgl_akhir)->subHour();

            if ($now->lt($reminderTime)) {
                return;
            }

            Event::whereDate('tgl_akhir', $date)
                ->where('reminder_sent', 0)
                ->update(['reminder_sent' => 1]);

            SystemNotification::create([
                'type'        => 'auction_reminder',
                'event_id'    => $event->id_event,
                'label'       => '⏰ Auction Akan Segera Berakhir',
                'description' => 'Hi, Onelito Koi Auction akan berakhir 1 jam ke depan, koi pilihan kamu jangan sampai terlewatkan 😊',
                'link'        => route('auction.index'),
                'status'      => 1,
            ]);

            $tanggalIndonesia = Carbon::parse($event->tgl_akhir)
                ->translatedFormat('d F Y');

            SendWhatsAppBroadcast::dispatch($tanggalIndonesia)
                ->onQueue('whatsapp');
        });
    }
}