<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{

    protected $keyType = 'integer';

    public $timestamps = false;

    protected $fillable = ['user_uid', 'moment', 'title', 'subtitle', 'url', 'rating', 'status_id', 'in_history', 'history_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function comments()
    {
        return $this->hasMany(PhotoComment::class);
    }

    public function photoEraser():void {
       $patch = $this->user_uid .'/photos/'.$this->url;
       Storage::disk('public')->delete($patch);
       History::query()->where('id', $this->history_id)->delete();
       $this->delete();
    }

}
