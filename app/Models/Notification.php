<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Notification extends Model
{

    protected $fillable = ['from_user', 'to_user', 'type_id', 'status_id', 'moment', 'note', 'data'];

    public $timestamps = false;

    public function fromUser() {
        return $this->hasOne(User::class, 'uid', 'from_user');
    }

    public function toUser() {
        return $this->hasOne(User::class, 'uid', 'to_user');
    }

    public static function isFeasibleToNotify($from, $to, $moment) {
       return  self::where('to_user', $to)
            ->where('from_user', $from)
            ->whereRaw('DAY(moment) =' . $moment->day . ' and  YEAR(moment) = '. $moment->year . ' and MONTH(moment) = ' . $moment->month)->count() > 2;
    }
}
