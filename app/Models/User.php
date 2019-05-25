<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'uid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'full_names', 'email', 'phone', 'address', 'nif', 'email_verified_at', 'password', 'avatar',
        'birthdate', 'sex', 'occupation', 'civil_status_id', 'birthplace', 'country', 'who_you_are', 'website', 'facebook',
        'twitter', 'religion_id', 'politics_id', 'status_id', 'remember_token', 'created_at', 'updated_at'];

    protected $hidden = [
      'password', 'remember_token'
    ];

    public function status () {
        return $this->belongsTo(UserStatus::class);
    }

    public function sex () {
        return $this->belongsTo(UserSex::class);
    }

    public function civil () {
        return $this->hasOne(UserCivilStatus::class, 'id', 'civil_status_id');
    }

    public function religion () {
        return $this->belongsTo(Religion::class);
    }

    public function politics () {
        return $this->belongsTo(Politics::class);
    }
}
