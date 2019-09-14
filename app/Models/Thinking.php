<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $user_uid
 * @property string $moment
 * @property string $title
 * @property string $subtitle
 * @property boolean $rating
 * @property string $note
 * @property boolean $status_id
 * @property boolean $in_history
 * @property integer $history_id
 */
class Thinking extends Model
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
    protected $fillable = ['user_uid', 'moment', 'title', 'subtitle', 'rating', 'note', 'status_id', 'in_history', 'history_id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

}
