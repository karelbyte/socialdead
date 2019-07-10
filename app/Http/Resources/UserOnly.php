<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserOnly extends JsonResource
{
    use Zodiac;

    public function toArray($request)
    {
        $avatar = $this->avatar === null ? Image::make($this->symbol($this->birthdate)['url'])->encode('data-url')
            : Image::make(storage_path('app/public/') . $this->uid . '/profile/avatar/' . $this->avatar)->encode('data-url', 50);

       return [
         'uid' => $this->uid,
         'full_names' =>  $this->full_names,
         'status'  => (string) $this->status_id,
         'moment_emit' => Carbon::now()->timestamp,
         'avatar' => $avatar->encoded,
         'occupation' => $this->occupation,
         'notifications' => new NotifySettings($this->settingNotifications)
       ];
    }
}
