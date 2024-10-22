<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'm_foto_produk';
    protected $primaryKey = 'id_foto_produk';
}
