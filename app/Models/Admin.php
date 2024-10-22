<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // column level
    const LEVEL_1 = 1; //superadmin

    protected $table = 'm_admin';
    protected $primaryKey = 'id_admin';

    protected $hidden = [
        'password',
    ];

    protected $guarded = [];

    /**
     * Password need to be all time encrypted.
     *
     * @param string $password
     */

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
