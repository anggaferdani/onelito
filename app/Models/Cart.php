<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 't_keranjang';
    protected $primaryKey = 'id_keranjang';

    public const EventFish = 'EventFish';
    public const Product = 'Product';
    public const KoiStock = 'KoiStock';

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_peserta');
    }

    public function cartable()
    {
        return $this->morphTo('cartable', 'cartable_type', 'cartable_id');
    }
}
