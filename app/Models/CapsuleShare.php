<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $capsule_id
 * @property string $to_user
 * @property Capsule $capsule
 */
class CapsuleShare extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'capsules_shares';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['capsule_id', 'to_user'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function capsule()
    {
        return $this->belongsTo(Capsule::class);
    }

    public $timestamps = false;
}
