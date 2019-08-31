<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $sub_reminder_id
 * @property string $to_user_uid
 * @property SubReminder $subReminder
 */
class SubReminderUser extends Model
{

    protected $table = 'sub_reminders_users';

    protected $keyType = 'integer';

    protected $fillable = ['sub_reminder_id', 'to_user_uid'];

    public $timestamps = false;
}
