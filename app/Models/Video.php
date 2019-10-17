<?php

namespace App\Models;

use App\Traits\UserFileStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use UserFileStore;

    protected $keyType = 'integer';

    public $timestamps = false;

    protected $fillable = ['user_uid', 'moment', 'title', 'subtitle', 'note', 'url', 'rating', 'status_id',
        'in_history', 'history_id', 'seze'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function videoEraser():void {
       $patch = $this->user_uid .'/videos/'.$this->url;
       $str = strlen($this->url);
       $pureName = substr($this->url, 0,  $str-4);
       $patch_tumbs =  $this->user_uid .'/videos/T'.  $pureName . '.PNG';
       History::query()->where('id', $this->history_id)->delete();
       Storage::disk('public')->delete($patch);
       Storage::disk('public')->delete($patch_tumbs);
       $this->restStore($this->user_uid, $this->size);
       $this->delete();
    }

}
