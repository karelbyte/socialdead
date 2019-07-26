<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $user_uid
 * @property string $moment
 * @property string $title
 * @property string $subtitle
 * @property string $note
 * @property boolean $type
 * @property integer $item_id
 * @property boolean $recurrent
 * @property User $user
 */
class Reminder extends Model
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
    protected $fillable = ['user_uid', 'moment', 'title', 'subtitle', 'note', 'type', 'item_id', 'recurrent'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_uid', 'uid');
    }

    public $timestamps = false;
}
