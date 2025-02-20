<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'm_kategori_produk';
    protected $primaryKey = 'id_kategori_produk';

    // const MAKANAN_IKAN = 'Makanan Ikan';
    // const PERLENGKAPAN_IKAN = 'Perlengkapan Ikan';
    // const IKAN = 'Ikan';
}
