<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class UserProfileGeneral extends JsonResource
{
    use Zodiac;

    public function toArray($request)
    {
        $avatar = $this->avatar === null ? Image::make($this->symbol($this->birthdate)['url'])->encode('data-url')
            : Image::make( storage_path('app/public/') . $this->uid . '/profile/avatar/' . $this->avatar)->encode('data-url', 50);
        return [
            'full_names' =>  $this->full_names,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'nif' => $this->nif,
            'birthdate' => $this->birthdate,
            'sex' => (string) $this->sex_id,
            'civil' => (string) $this->civil_status_id,
            'birthplace' => $this->birthplace,
            'country' => $this->country,
            'who_you_are' => $this->who_you_are,
            'website' => $this->website,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'religion' => $this->religion,
            'politics' => $this->politics,
            'avatar' => $avatar->encoded,
            'occupation' => $this->occupation,
            'zodiac' => $this->symbol($this->birthdate)
        ];
    }
}
