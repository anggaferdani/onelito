<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'm_event';
    protected $primaryKey = 'id_event';
    protected $guarded = [];

    public const EVENT = 'Event';
    public const REGULAR = 'Regular';

    protected $casts = [
        'tgl_mulai' => 'datetime',
        'tgl_akhir' => 'datetime',
    ];

    public function getTglMulaiWibAttribute(): ?string
    {
        if (!$this->tgl_mulai) {
            return null;
        }

        return $this->tgl_mulai->setTimezone('Asia/Jakarta')->format('Y-m-d H:i');
    }

    public function getTglAkhirWibAttribute(): ?string
    {
        if (!$this->tgl_akhir) {
            return null;
        }
        return $this->tgl_akhir->setTimezone('Asia/Jakarta')->format('Y-m-d H:i');
    }

    public function auctionProducts()
    {
        return $this->hasMany(EventFish::class, 'id_event')->where('status_aktif', 1);
    }

    public function biddings()
    {
        return $this->hasManyThrough(
            LogBid::class,
            EventFish::class,
            'id_event',
            'id_ikan_lelang',
            'id_event',
            'id_ikan'
        );
    }
}
