<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;

    protected $table = 'alamats';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function peserta() {
        return $this->belongsTo(Member::class, 'peserta_id', 'id_peserta');
    }
}
