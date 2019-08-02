<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $reminder_id
 * @property boolean $type
 * @property integer $item_id
 * @property Reminder $reminder
 */
class ReminderDetail extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'reminders_details';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['reminder_id', 'type', 'item_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reminder()
    {
        return $this->belongsTo(Reminder::class);
    }

    public $timestamps = false;
}
