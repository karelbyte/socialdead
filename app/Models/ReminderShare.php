<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $reminder_id
 * @property string $from_user
 * @property string $to_user
 * @property Reminder $reminder
 */
class ReminderShare extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'reminders_shares';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['reminder_id', 'from_user', 'to_user', 'extend'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reminder()
    {
        return $this->belongsTo(Reminder::class, 'reminder_id', 'id');
    }

    public $timestamps = false;
}
