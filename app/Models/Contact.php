<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Contact extends Model
{


    protected $fillable = ['user_uid', 'contact_user_uid', 'type_id', 'kin_id',  'status_id'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function contact()
    {
        return $this->belongsTo(User::class, 'contact_user_uid', 'uid');
    }

    public function kin()
    {
        return $this->belongsTo(Kin::class, 'kin_id', 'id');
    }

    public function scopeConstable($query)
    {
        return $query->where('constable', 2);
    }
}
