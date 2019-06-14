<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserJob extends Model
{

    protected $table = 'users_jobs';


    protected $fillable = ['user_id', 'place', 'period_time', 'review'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public $timestamps = false;
}
