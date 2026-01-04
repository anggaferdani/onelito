<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\LogBid;
use App\Models\Member;
use App\Models\EventFish;
use App\Models\Notification;
use App\Models\NotificationLog;
use App\Services\AuctionTimeService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessOutbidNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $fishId;
    protected int $currentBidderId;

    public function __construct(int $fishId, int $currentBidderId)
    {
        $this->fishId = $fishId;
        $this->currentBidderId = $currentBidderId;
    }

    public function handle(): void
    {
        $fish = EventFish::with('event')->find($this->fishId);
        if (!$fish) {
            return;
        }

        if (!AuctionTimeService::isOutbidSession($fish)) {
            return;
        }

        $previousBidderIds = LogBid::where('id_ikan_lelang', $this->fishId)
            ->where('id_peserta', '!=', $this->currentBidderId)
            ->pluck('id_peserta')
            ->unique();

        if ($previousBidderIds->isEmpty()) {
            return;
        }

        foreach ($previousBidderIds as $bidderId) {

            DB::transaction(function () use ($bidderId, $fish) {

                $existingLog = NotificationLog::where([
                        'id_peserta' => $bidderId,
                        'id_ikan_lelang' => $fish->id_ikan,
                        'session_type' => 'outbid',
                    ])
                    ->lockForUpdate()
                    ->first();

                if ($existingLog) {
                    return;
                }

                $member = Member::find($bidderId);
                if (!$member) {
                    return;
                }

                $notification = Notification::create([
                    'peserta_id' => $member->id_peserta,
                    'label' => '⚠️ Koi Auction Alert',
                    'description' => "Hi Mr / Ms. {$member->nama}, bid kamu pada koi {$fish->no_ikan} sudah terlampaui oleh peserta lain. Yuk segera cek dan bid kembali sebelum waktu lelang berakhir!",
                    'link' => route('auction.bid', ['idIkan' => $fish->id_ikan]),
                    'status' => 1,
                ]);

                NotificationLog::create([
                    'id_peserta' => $member->id_peserta,
                    'id_ikan_lelang' => $fish->id_ikan,
                    'notification_id' => $notification->id,
                    'session_type' => 'outbid',
                    'created_at' => Carbon::now(),
                ]);
            });
        }
    }
}
