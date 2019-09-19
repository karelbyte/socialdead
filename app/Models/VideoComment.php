<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $video_id
 * @property string $from_user
 * @property string $note
 * @property Video $video
 */
class VideoComment extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'videos_comments';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['video_id', 'from_user', 'note'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public $timestamps = false;
}
