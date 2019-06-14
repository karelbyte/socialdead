<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $user_uid
 * @property string $music
 * @property string $tv
 * @property string $movies
 * @property string $games
 * @property string $writers
 * @property string $others
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 */
class UserHobbies extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'users_hobbies';

    /**
     * @var array
     */
    protected $fillable = ['user_uid',  'hobby', 'music', 'tv', 'movies', 'games', 'writers', 'others'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public $timestamps = false;

}
