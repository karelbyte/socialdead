<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $capsule_id
 * @property string $user_uid
 * @property Capsule $capsule
 */
class CapsuleConstable extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'capsules_constables';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['capsule_id', 'user_uid', 'key', 'authoridez'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function capsule()
    {
        return $this->belongsTo(Capsule::class);
    }

    public $timestamps = false;
}
