<?php

namespace App\Models\Bot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PemenangLelang extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_bot';
    protected $table = 'auction_results';
    protected $guarded = ['id'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'id', 'user_id');
    }
}
