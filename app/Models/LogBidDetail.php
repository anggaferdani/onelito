<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBidDetail extends Model
{
    use HasFactory;

    protected $table = 't_log_bidding_detail';
    protected $primaryKey = 'id_bidding_detail';
    protected $guarded = [];

    protected $casts = [
        'nominal_bid' => 'integer'
    ];

    public function logBid()
    {
        return $this->belongsTo(LogBid::class, 'id_bidding');
    }
}
