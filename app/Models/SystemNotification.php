<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    use HasFactory;

    protected $table = 'system_notifications';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'system_notification_id', 'id');
    }
}
