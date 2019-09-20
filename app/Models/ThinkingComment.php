<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $thinking_id
 * @property string $from_user
 * @property string $note
 * @property Thinking $thinking
 */
class ThinkingComment extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'thinkings_comments';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['thinking_id', 'from_user', 'note', 'moment'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thinking()
    {
        return $this->belongsTo('App\Thinking');
    }

    public $timestamps = false;
}
