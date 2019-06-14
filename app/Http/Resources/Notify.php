<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Notify extends JsonResource
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
            'note_short' => strlen($this->note) > 20 ?  substr($this->note, 0, 20) . '...' : $this->note,
            'note'=> $this->note,
            'type' => 1 // AMISTAD
        ];
    }
}
