<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotifyFull extends JsonResource
{
    use Zodiac;

    public function toArray($request)
    {

        $avatar = $this->fromUser->avatar === null ?  $this->symbol($this->fromUser->birthdate)['url']
            : url('/') . $this->fromUser->avatar;

        return [
            'id' => $this->id,
            'name' => $this->fromUser->full_names,
            'avatar' => $avatar,
            'note' =>  $this->note,
            'moment'=> Carbon::parse($this->moment)->format('d-m-Y H:i'),
            'occupation' =>  $this->fromUser->occupation,
            'type' => 1 // AMISTAD
        ];
    }
}
