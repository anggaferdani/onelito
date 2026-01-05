<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventFish extends Model
{
    use HasFactory;

    protected $table = 'm_ikan_lelang';
    protected $primaryKey = 'id_ikan';
    protected $guarded = [];

    // ✅ CORRECT: Get bid details melalui LogBid (hasManyThrough)
    public function bidDetails()
    {
        return $this->hasManyThrough(
            LogBidDetail::class,
            LogBid::class,
            'id_ikan_lelang',
            'id_bidding',
            'id_ikan',
            'id_bidding'
        )->where('t_log_bidding_detail.status_aktif', 1)
          ->where('t_log_bidding.status_aktif', 1);
    }

    // ✅ Get latest 10 bid details dengan member info
    public function latestBidDetails()
    {
        return $this->bidDetails()
            ->with('logBid.member')
            ->orderBy('t_log_bidding_detail.nominal_bid', 'desc')
            ->orderBy('t_log_bidding_detail.id_bidding_detail', 'desc')
            ->limit(10);
    }

    public function photo()
    {
        return $this->hasOne(FishPhoto::class, 'id_ikan');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function bids()
    {
        return $this->hasMany(LogBid::class, 'id_ikan_lelang')
            ->where('status_aktif', 1)
            ->orderBy('nominal_bid', 'desc')
            ->orderBy('updated_at', 'desc');
    }

    public function maxBid()
    {
        return $this->hasOne(LogBid::class, 'id_ikan_lelang')
            ->where('status_aktif', 1)
            ->orderBy('nominal_bid', 'desc')
            ->orderBy('waktu_bid', 'asc');
    }

    public function userBid($idPeserta)
    {
        return $this->hasOne(LogBid::class, 'id_ikan_lelang', 'id_ikan')
            ->where('id_peserta', $idPeserta);
    }

    public function winners()
    {
        return $this->hasManyThrough(
            AuctionWinner::class,
            LogBid::class,
            'id_ikan_lelang',
            'id_bidding'
        )->where('t_pemenang_lelang.status_aktif', 1);
    }

    public function members()
    {
        return $this->hasManyThrough(
            Member::class,
            LogBid::class,
            'id_ikan_lelang',
            'id_peserta'
        )->where('m_peserta.status_aktif', 1);
    }

    public function wishlist()
    {
        return $this->morphOne(Wishlist::class, 'wishlistable', 'wishlistable_type', 'wishlistable_id');
    }

    public function cartable()
    {
        return $this->morphOne(Cart::class, 'cartable', 'cartable_type', 'cartable_id');
    }
}
