<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Collection;

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
    protected $fillable = ['user_uid', 'to_user_email', 'to_user_email_cc', 'to_user_email_cc', 'token', 'note', 'moment', 'status_id'];

    protected $table = 'sub_reminders';


    public function UserOwner()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public $timestamps = false;
}
