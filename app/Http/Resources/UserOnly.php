<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserOnly extends JsonResource
{
    use Zodiac;

    public function toArray($request)
    {
        $avatar = $this->avatar === null ? $this->symbol($this->birthdate)['url']
            : url('/') . $this->avatar;

       return [
         'uid' => $this->uid,
         'full_names' =>  $this->full_names,
         'status'  => (string) $this->status_id,
         'moment_emit' => Carbon::now()->timestamp,
         'avatar' => $avatar,
         'occupation' => $this->occupation,
         'notifications' => new NotifySettings($this->settingNotifications)
       ];
    }
}
