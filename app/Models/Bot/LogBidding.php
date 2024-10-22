<?php

namespace App\Models\Bot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogBidding extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_bot';
    protected $table = 'bidder';
    protected $guarded = ['id'];

    public function produk()
    {
        return $this->belongsTo(Bid::class, 'bid_id', 'bid_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id', 'user_id');
    }
}
