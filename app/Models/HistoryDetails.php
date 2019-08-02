<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $history_id
 * @property boolean $type
 * @property string $item
 * @property string $note
 * @property boolean $status_id
 * @property History $history
 */
class HistoryDetails extends Model
{

    protected $table = 'histories_details';

    protected $keyType = 'integer';

    protected $fillable = ['history_id', 'type', 'item', 'note', 'status_id'];

    public $timestamps = false;

    public function history()
    {
        return $this->belongsTo(History::class, 'id', 'history_id');
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'item', 'id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class, 'item', 'id');
    }

    public function audio()
    {
        return $this->belongsTo(Audio::class, 'item', 'id');
    }
}
