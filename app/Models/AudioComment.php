<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $audio_id
 * @property string $from_user
 * @property string $note
 * @property Audio $audio
 */
class AudioComment extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'audios_comments';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['audio_id', 'from_user', 'note', 'moment'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function audio()
    {
        return $this->belongsTo('App\Audio');
    }

    public $timestamps = false;
}
