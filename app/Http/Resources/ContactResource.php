<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class ContactResource extends JsonResource
{
    use Zodiac;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $avatar = $this->avatar === null ? Image::make($this->symbol($this->birthdate)['url'])->encode('data-url')
            : Image::make(storage_path('app/public/') . $this->uid . '/profile/avatar/' . $this->avatar)->encode('data-url', 50);

        return [
            'uid' => $this->contact_user_uid,
            'full_names' => (string) $this->full_names,
            'online'  => $this->status_id,
            'type' =>  $this->type_id,
            'avatar' => $avatar->encoded
        ];
    }
}
