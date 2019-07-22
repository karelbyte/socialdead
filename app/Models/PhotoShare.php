<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoShare extends Model
{
   protected $table = 'photos_shares';

   public $timestamps = false;

   protected $fillable = [
       'photo_id',
       'to_user',
       'from_user',
       'moment'
   ];

    public function User() {
        return $this->hasOne(User::class, 'uid', 'from_user');
    }

    public function Photo() {
        return $this->hasOne(Photo::class, 'id', 'photo_id');
    }
}
