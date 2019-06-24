<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $user_uid
 * @property string $for_user_uid
 * @property string $msj
 * @property boolean $status_id
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 */
class Chat extends Model
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
    protected $fillable = ['user_uid', 'for_user_uid', 'msj', 'type', 'status_id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_uid', 'uid');
    }
}
