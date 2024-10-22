<?php

namespace App\Models\Bot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bid extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_bot';
    protected $primaryKey = 'bid_id';
    protected $table = 'bid';
    protected $guarded = ['bid_id'];
}
