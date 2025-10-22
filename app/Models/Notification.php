<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function peserta() {
        return $this->belongsTo(Member::class, 'peserta_id', 'id_peserta');
    }

    public function systemNotification()
    {
        return $this->belongsTo(SystemNotification::class, 'system_notification_id', 'id');
    }
}
