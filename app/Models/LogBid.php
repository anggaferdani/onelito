<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBid extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 't_log_bidding';
    protected $primaryKey = 'id_bidding';

    protected $casts = [
        'nominal_bid' => 'integer'
    ];

    public function eventFish()
    {
        return $this->belongsTo(EventFish::class, 'id_ikan_lelang');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_peserta');
    }

    public function winner()
    {
        return $this->hasOne(AuctionWinner::class, 'id_bidding');
    }
}
