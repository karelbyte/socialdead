<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSearch extends JsonResource
{
    use Zodiac;

    public function toArray($request)
    {
        $avatar = $this->avatar === null ? $this->symbol($this->birthdate)['url'] : url('/') . $this->avatar;

        return [
            'value' => $this->uid,
            'label' =>  $this->full_names,
            'sex' =>  $this->sex_id,
            'occupation' => $this->occupation,
            'country' => $this->country,
            'avatar' => $avatar
        ];
    }
}
