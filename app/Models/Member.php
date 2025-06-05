<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class Member extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    protected $table = 'm_peserta';
    protected $primaryKey = 'id_peserta';

    protected $hidden = [
        'password',
    ];

    public static function generateVerificationToken()
    {
        return Str::random(60);
    }

    public static function generateVerificationCode()
    {
        return mt_rand(100000, 999999); // Kode 6 digit
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'provinsi');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'kota');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'kecamatan');
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class, 'kelurahan');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'id_peserta');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'id_peserta');
    }

    public function biddings()
    {
        return $this->hasMany(LogBid::class, 'id_peserta');
    }

    public function orders()
    {
        return $this->hasMany(OrderDetail::class, 'id_peserta');
    }

    public function alamats() {
        return $this->hasMany(Alamat::class, 'peserta_id', 'id_peserta');
    }

    public function loginHistories() {
        return $this->hasMany(LoginHistory::class, 'peserta_id', 'id_peserta');
    }
}
