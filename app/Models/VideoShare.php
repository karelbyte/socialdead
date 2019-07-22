<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoShare extends Model
{
    protected $table = 'videos_shares';

    public $timestamps = false;

    protected $fillable = [
        'video_id',
        'to_user',
        'from_user',
        'moment'
    ];

    public function User() {
        return $this->hasOne(User::class, 'uid', 'from_user');
    }

    public function Video() {
        return $this->hasOne(Video::class, 'id', 'video_id');
    }
}
