<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;

    protected $table = 'login_histories';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function peserta() {
        return $this->belongsTo(Member::class, 'peserta_id', 'id_peserta');
    }
}
