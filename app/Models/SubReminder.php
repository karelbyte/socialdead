<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $user_uid
 * @property string $to_user_uid
 * @property string $to_user_email
 * @property string $token
 * @property string $note
 * @property string $moment
 * @property boolean $status_id
 */
class SubReminder extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['user_uid', 'to_user_uid', 'to_user_email', 'token', 'note', 'moment', 'status_id'];

    protected $table = 'sub_reminders';

    public $timestamps = false;
}
