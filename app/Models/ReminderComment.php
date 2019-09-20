<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $reminder_id
 * @property string $from_user
 * @property string $note
 * @property string $moment
 * @property Reminder $reminder
 */
class ReminderComment extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'reminders_comments';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['reminder_id', 'from_user', 'note', 'moment'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reminder()
    {
        return $this->belongsTo('App\Reminder');
    }

    public $timestamps = false;
}
