<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChampionFish extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'm_champion_koi';
    protected $primaryKey = 'id_champion_koi';
}
