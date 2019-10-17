<?php

namespace App\Models;

use App\Traits\UserFileStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Audio extends Model
{
   use UserFileStore;

    protected $table = 'audios';

    protected $keyType = 'integer';

    public $timestamps = false;

    protected $fillable = ['user_uid', 'moment', 'title', 'subtitle', 'note', 'url', 'rating', 'status_id', 'in_history', 'history_id', 'size'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function audioEraser():void {
       $patch = $this->user_uid .'/audios/'.$this->url;
       Storage::disk('public')->delete($patch);
       History::query()->where('id', $this->history_id)->delete();
       $this->restStore($this->user_uid, $this->size);
       $this->delete();
    }

}
