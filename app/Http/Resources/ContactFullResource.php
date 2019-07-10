<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class ContactFullResource extends JsonResource
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
            : Image::make(storage_path('app/public/') .  $this->contact_user_uid . '/profile/avatar/' . $this->avatar)->encode('data-url');

        return [
            'uid' => $this->contact_user_uid,
            'names' =>  $this->full_names,
            'status'  =>  $this->status_user,
            'type'  =>  $this->type_id === 1 ? ['id' => '1', 'descriptor' =>'Amigo'] :
                ['id' => '2', 'descriptor' =>'Familia'],
            'kin' => $this->kin,
            'avatar' => $avatar->encoded,
            'constable' => (bool) $this->constable,
            'who_you_are' => substr($this->who_you_are, 0, 120) . '...',
            'occupation' => $this->occupation,
        ];
    }
}
