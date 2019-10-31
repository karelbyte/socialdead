<?php

namespace App\Http\Resources;

use App\Models\Admin\AdminUser;
use App\Traits\Zodiac;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class Notify extends JsonResource
{
    use Zodiac;

    public function toArray($request)
    {

        if ($this->type_id !== 10) {

            $avatar = $this->fromUser->avatar === null ? Image::make($this->symbol($this->birthdate)['url'])->encode('data-url')
                : Image::make(storage_path('app/public/') . $this->fromUser->uid . '/profile/avatar/' . $this->fromUser->avatar)
                    ->resize(150, 150)->encode('data-url', 50);

            $name = $this->fromUser->full_names;

        } else {

            $patch = storage_path('app/public/') . '/social/social.png';

            $avatar = Image::make($patch )->resize(200, 150)->encode('data-url', 50);

            $fromUser = AdminUser::query()->find($this->from_user);

            $name = '(SOCIALDEAD) ' . $fromUser->names;
        }


        return [
            'id' => $this->id,
            'name' => $name,
            'avatar' => $avatar->encoded,
            'note_short' => strlen($this->note) > 20 ?  substr($this->note, 0, 20) . '...' : $this->note,
            'note'=> $this->note,
            'type' => $this->type_id,
            'data' => $this->data
        ];
    }
}
