<?php

namespace App\Models\Bot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_bot';
    protected $primaryKey = 'user_id';
    protected $table = 'member_table';
    protected $guarded = ['user_id'];
    public $dates = ['upload_time', 'tanggal_lahir'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
