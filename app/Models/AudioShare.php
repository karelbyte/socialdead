<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioShare extends Model
{
    protected $table = 'audios_shares';

    public $timestamps = false;

    protected $fillable = [
        'audio_id',
        'to_user',
        'from_user',
        'moment'
    ];

    public function User() {
        return $this->hasOne(User::class, 'uid', 'from_user');
    }

    public function Audio() {
        return $this->hasOne(Audio::class, 'id', 'audio_id');
    }
}
