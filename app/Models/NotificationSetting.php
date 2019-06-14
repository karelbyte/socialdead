<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{

    protected $table = 'notifications_settings';

    protected $fillable = ['user_uid', 'notification_sound', 'notification_email', 'notification_reminders', 'chat_sound'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public $timestamps = false;
}
