<?php

namespace App\Models\Bot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lelang extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_bot';
    protected $table = 'm_lelang';
    protected $guarded = ['id'];
    public $dates = ['mulai_lelang','selesai_lelang'];

    public function bid()
    {
        return $this->hasMany(LogBidding::class,'id_lelang','id');
    }

    public function produk()
    {
        return $this->hasMany(LogBidding::class,'nama_produk','nama');
    }
}
