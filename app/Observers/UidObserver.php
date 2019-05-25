<?php

namespace App\Observers;


use Illuminate\Support\Str;

class UidObserver
{
    public function creating($model)
    {
        if (empty($model->uid)) {
            $model->uid = Str::uuid();
        }
    }
}
