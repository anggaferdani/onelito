<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FishPhoto extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'm_foto_ikan';
    protected $primaryKey = 'id_foto_ikan';
}
