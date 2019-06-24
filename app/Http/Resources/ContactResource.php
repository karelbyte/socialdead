<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $avatar = $this->avatar === null ? $this->symbol($this->birthdate)['url'] : url('/') . $this->avatar;
        return [
            'uid' => $this->contact_user_uid,
            'full_names' =>  $this->full_names,
            'online'  => $this->status_id,
            'type' =>  $this->type_id,
            'avatar' => $avatar,
        ];
    }
}
