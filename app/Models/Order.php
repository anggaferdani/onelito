<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 't_order';
    protected $primaryKey = 'id_order';

    public const SENT = 'Dikirim';
    public const WAITING = 'Menunggu Dikirim';
    public const DONE = 'Selesai';

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'id_order');
    }

    public function latestDetail()
    {
        return $this->hasOne(OrderDetail::class, 'id_order')->latestOfMany('id_order_detail')
            ->select('t_order_detail.*');
    }
}
