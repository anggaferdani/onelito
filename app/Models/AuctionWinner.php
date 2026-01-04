<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionWinner extends Model
{
    use HasFactory;

    protected $table = 't_pemenang_lelang';
    protected $primaryKey = 'id_pemenang_lelang';
    protected $guarded = [];

    public function bidding()
    {
        return $this->belongsTo(LogBid::class, 'id_bidding');
    }

    public function member()
    {
        return $this->hasOneThrough(
            Member::class,
            LogBid::class,
            'id_bidding',
            'id_peserta',
            'id_bidding',
            'id_peserta'
        );
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
}
