<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionWinner extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 't_pemenang_lelang';
    protected $primaryKey = 'id_pemenang_lelang';

    public function bidding()
    {
        return $this->belongsTo(LogBid::class, 'id_bidding');
    }

    // relation avaible for join
    public function member()
    {
        return $this->belongsTo(Member::class, 'id_peserta');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
}
