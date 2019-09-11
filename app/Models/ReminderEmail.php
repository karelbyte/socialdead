<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $reminder_id
 * @property string $email
 * @property Reminder $reminder
 */
class ReminderEmail extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'reminders_emails';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['reminder_id', 'email', 'token', 'status_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public $timestamps = false;

}
