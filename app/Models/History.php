<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $user_id
 * @property string $moment
 * @property string $title
 * @property string $subtitle
 * @property boolean $status_id
 * @property User $user
 */
class History extends Model
{

    protected $keyType = 'integer';

    protected $fillable = ['user_id', 'moment', 'title', 'type', 'subtitle', 'status_id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User', null, 'uid');
    }

    public function details()
    {
        return $this->hasMany(HistoryDetails::class);
    }
}
