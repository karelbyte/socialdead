<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileGeneral extends JsonResource
{
    use Zodiac;

    public function toArray($request)
    {
        $avatar = $this->avatar === null ? $this->symbol($this->birthdate)['url'] : url('/') . $this->avatar;

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
            'avatar' => $avatar,
            'occupation' => $this->occupation,
            'zodiac' => $this->symbol($this->birthdate)
        ];
    }
}
