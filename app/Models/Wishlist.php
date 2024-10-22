<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 't_wishlist';
    protected $primaryKey = 'id_wishlist';

    public const EventFish = 'EventFish';
    public const Product = 'Product';
    public const KoiStock = 'KoiStock';

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_peserta');
    }

    // public function product()
    // {
    //     return $this->belongsTo(Product::class, 'id_produk');
    // }

    public function wishlistable()
    {
        return $this->morphTo('wishlistable', 'wishlistable_type', 'wishlistable_id')
        ->morphWithCount([
            EventFish::class => ['bidDetails'],
        ]);

    }
}
