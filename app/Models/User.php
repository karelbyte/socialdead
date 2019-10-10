<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'uid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'secret',
        'full_names', 'email', 'phone', 'address', 'nif', 'email_verified_at', 'password', 'avatar',
        'birthdate', 'sex_id', 'occupation', 'civil_status_id', 'birthplace', 'country', 'who_you_are', 'website', 'facebook',
        'twitter', 'religion_id', 'politics_id', 'status_id', 'remember_token'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    protected $hidden = [
      'password', 'remember_token'
    ];

    public function status () {
        return $this->hasOne(UserStatus::class, 'id', 'status_id');
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

    public function notifications () {
        return $this->hasMany(Notification::class, 'to_user', 'uid')->where('status_id', 1 );
    }

    public function notificationsAll () {
        return $this->hasMany(Notification::class, 'to_user', 'uid')->wherein('status_id', [1, 3] );
    }


    public function settingNotifications () {
        return $this->hasone(NotificationSetting::class, 'user_uid', 'uid');
    }

    public function contacts ()
    {
        return $this->hasMany(Contact::class);
    }

    public function isContact($uid) {
       $_contact =  Contact::query()->where('user_uid', $this->uid)->where('contact_user_uid', $uid)->first();
       return  $_contact === null;
    }

    public function Jobs() {
        return $this->hasMany(UserJob::class, 'user_uid', 'uid');
    }

    public function Hobbies() {
        return $this->hasOne(UserHobbies::class, 'user_uid', 'uid');
    }

    public function Photos() {
        return $this->hasMany(Photo::class, 'user_uid', 'uid')->orderBy('photos.moment', 'desc');
    }

    // FOTOS PUBLICAS ULTIMOS 10 DIAS
    public function PhotosPublic() {
        return $this->hasMany(Photo::class, 'user_uid', 'uid')
            ->orderBy('photos.moment', 'desc')
            ->whereRaw('datediff(now(), photos.moment) <= 10')
            ->where('status_id', 1);
    }

    public function Videos() {
        return $this->hasMany(Video::class, 'user_uid', 'uid')->orderBy('videos.moment', 'desc');
    }

    public function Store() {
        return $this->hasOne(UserStore::class, 'user_uid', 'uid');
    }

    public function Audios() {
        return $this->hasMany(Audio::class, 'user_uid', 'uid')->orderBy('audios.moment', 'desc');
    }


}
