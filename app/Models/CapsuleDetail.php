<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $capsule_id
 * @property boolean $type
 * @property integer $item_id
 * @property Capsule $capsule
 */
class CapsuleDetail extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'capsules_details';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['capsule_id', 'type', 'item_id', 'doc'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function capsule()
    {
        return $this->belongsTo(Capsules::class);
    }

    public $timestamps = false;
}
