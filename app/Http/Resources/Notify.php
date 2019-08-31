<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class Notify extends JsonResource
{
    use Zodiac;

    public function toArray($request)
    {

       /* $avatar = $this->fromUser->avatar === null ?  $this->symbol($this->fromUser->birthdate)['url']
            : url('/') . $this->fromUser->avatar;*/

        $avatar = $this->fromUser->avatar === null ? Image::make($this->symbol($this->birthdate)['url'])->encode('data-url')
            : Image::make(storage_path('app/public/') . $this->fromUser->uid . '/profile/avatar/' . $this->fromUser->avatar)
                ->resize(150, 150)->encode('data-url', 50);

        return [
            'id' => $this->id,
            'name' => $this->fromUser->full_names,
            'avatar' => $avatar->encoded,
            'note_short' => strlen($this->note) > 20 ?  substr($this->note, 0, 20) . '...' : $this->note,
            'note'=> $this->note,
            'type' => $this->type_id,
            'data' => $this->data
        ];
    }
}
