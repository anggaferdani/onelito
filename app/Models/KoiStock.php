<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoiStock extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'm_koi_stock';
    protected $primaryKey = 'id_koi_stock';

    public function wishlist()
    {
        return $this->morphOne(Wishlist::class, 'wishlistable', 'wishlistable_type', 'wishlistable_id');
    }
}
