<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\EventFish;
use App\Models\LogBid;

class AuctionTimeService
{
    public static function getFinalEndTime(EventFish $fish): Carbon
    {
        if (!$fish->event || !$fish->event->tgl_akhir) {
            return now();
        }

        $eventEnd    = Carbon::parse($fish->event->tgl_akhir);
        $extraMinute = (int) ($fish->extra_time ?? 0);

        if ($extraMinute <= 0) {
            return $eventEnd;
        }

        $finalEnd = $eventEnd->copy()->addMinutes($extraMinute);

        $lastBid = LogBid::where('id_ikan_lelang', $fish->id_ikan)
            ->orderByDesc('waktu_bid')
            ->first();

        if (!$lastBid || !$lastBid->waktu_bid) {
            return $finalEnd;
        }

        $lastBidTime = Carbon::parse($lastBid->waktu_bid);

        if ($lastBidTime->greaterThanOrEqualTo($eventEnd)) {

            $rollingEnd = $lastBidTime->copy()->addMinutes($extraMinute);

            if ($rollingEnd->greaterThan($finalEnd)) {
                $finalEnd = $rollingEnd;
            }
        }

        return $finalEnd;
    }

    public static function isFishEnded(EventFish $fish): bool
    {
        return now()->greaterThanOrEqualTo(
            self::getFinalEndTime($fish)
        );
    }

    public static function isOutbidSession(EventFish $fish): bool
    {
        if (!$fish->event || !$fish->event->tgl_akhir) {
            return false;
        }

        $now        = now();
        $eventEnd  = Carbon::parse($fish->event->tgl_akhir);
        $finalEnd  = self::getFinalEndTime($fish);

        $outbidStart = $eventEnd->copy()->subMinutes(15);

        return $now->between($outbidStart, $finalEnd);
    }

    public static function isExtraTime(EventFish $fish): bool
    {
        if (!$fish->event || !$fish->event->tgl_akhir) {
            return false;
        }

        $now       = now();
        $eventEnd = Carbon::parse($fish->event->tgl_akhir);
        $finalEnd = self::getFinalEndTime($fish);

        return $now->greaterThan($eventEnd)
            && $now->lessThanOrEqualTo($finalEnd);
    }

    public static function remainingSeconds(EventFish $fish): int
    {
        $finalEnd = self::getFinalEndTime($fish);

        if (now()->greaterThanOrEqualTo($finalEnd)) {
            return 0;
        }

        return now()->diffInSeconds($finalEnd);
    }

    public static function getOutbidSessionType(EventFish $fish): ?string
    {
        return self::isOutbidSession($fish)
            ? 'outbid'
            : null;
    }

    public static function extendExtraTime(EventFish $fish): void
    {
        if (self::isFishEnded($fish)) {
            return;
        }

        if (!self::isExtraTime($fish)) {
            return;
        }
    }
}
