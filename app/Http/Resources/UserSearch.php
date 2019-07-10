<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class UserSearch extends JsonResource
{
    use Zodiac;

    public function toArray($request)
    {
        $avatar = $this->avatar === null ? Image::make($this->symbol($this->birthdate)['url'])->encode('data-url')
            : Image::make(storage_path('app/public/') . $this->uid . '/profile/avatar/' . $this->avatar)->encode('data-url');

        return [
            'value' => $this->uid,
            'label' =>  $this->full_names,
            'sex' =>  $this->sex_id,
            'occupation' => $this->occupation,
            'country' => $this->country,
            'avatar' => $avatar->encoded
        ];
    }
}
