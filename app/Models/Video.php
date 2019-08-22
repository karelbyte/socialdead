<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{

    protected $keyType = 'integer';

    public $timestamps = false;

    protected $fillable = ['user_uid', 'moment', 'title', 'subtitle', 'url', 'rating', 'status_id', 'in_history', 'history_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function videoEraser():void {
       $patch = $this->user_uid .'/videos/'.$this->url;
       $str = strlen($this->url);
       $pureName = substr($this->url, 0,  $str-4);
       $patch_tumbs =  $this->user_uid .'/videos/'.  $pureName . '.png';
       Storage::disk('public')->delete($patch);
       Storage::disk('public')->delete($patch_tumbs);
       $this->delete();
    }

}
