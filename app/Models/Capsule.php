<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Capsule extends Model
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
    protected $fillable = ['user_uid', 'moment', 'title', 'subtitle', 'note', 'opendate', 'activate', 'recurrent'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function constables()
    {
        return $this->hasMany(CapsuleConstable::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(CapsuleDetail::class);
    }

    public function photos()
    {
        return $this->hasMany(CapsuleDetail::class, 'capsule_id', 'id')->where('type', 3);
    }

    public function videos()
    {
        return $this->hasMany(CapsuleDetail::class, 'capsule_id', 'id')->where('type', 1);
    }

    public function medias()
    {
        return $this->hasMany(CapsuleDetail::class, 'capsule_id', 'id')->whereIn('type', [1, 2]);
    }

    public function audios()
    {
        return $this->hasMany(CapsuleDetail::class, 'capsule_id', 'id')->where('type', 2);
    }

    public function emails()
    {
        return $this->hasMany(CapsuleEmail::class, 'capsule_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shares()
    {
        return $this->hasMany(CapsuleShare::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }


    public function keyCypher()
    {
        $key = '';
        foreach ($this->constables as $constable) {
            $key .= $constable->key;
        }
        return $key;
    }

    public $timestamps = false;
}
