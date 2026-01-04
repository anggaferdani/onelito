<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    protected $table = 'notification_logs';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_peserta', 'id_peserta');
    }
    
    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id', 'id');
    }
    
    public function eventFish()
    {
        return $this->belongsTo(EventFish::class, 'id_ikan_lelang', 'id_ikan');
    }
}
