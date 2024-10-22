<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 't_order_detail';
    protected $primaryKey = 'id_order_detail';

    public const EventFish = 'EventFish';
    public const Product = 'Product';
    public const KoiStock = 'KoiStock';

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_peserta');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');

    }

    public function productable()
    {
        return $this->morphTo('productable', 'productable_type', 'productable_id')
        ->morphWith([
            Product::class => ['photo', 'category'],
            EventFish::class => ['photo'],
        ]);
    }
}
