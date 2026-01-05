<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBid extends Model
{
    use HasFactory;

    protected $table = 't_log_bidding';
    protected $primaryKey = 'id_bidding';
    protected $guarded = [];

    protected $casts = [
        'nominal_bid' => 'integer'
    ];

    public function eventFish()
    {
        return $this->belongsTo(EventFish::class, 'id_ikan_lelang', 'id_ikan');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_peserta', 'id_peserta');
    }

    public function winner()
    {
        return $this->hasOne(AuctionWinner::class, 'id_bidding');
    }

    // ✅ ADD: Relationship to details
    public function details()
    {
        return $this->hasMany(LogBidDetail::class, 'id_bidding', 'id_bidding');
    }
}