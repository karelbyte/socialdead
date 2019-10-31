<?php

namespace App\Http\Resources\Admin;

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

        $patch = storage_path('app/public/') . '/social/social.png';

        $avatar = Image::make($patch )->resize(200, 150)->encode('data-url', 50)->encoded;

        $fromUser = AdminUser::query()->find($this->from_user);

        return [
            'id' => $this->id,
            'name' => '(SOCIALDEAD) ' . $fromUser->names,
            'avatar' => $avatar,
            'note_short' => strlen($this->note) > 20 ?  substr($this->note, 0, 20) . '...' : $this->note,
            'note'=> $this->note,
            'type' => $this->type_id,
            'data' => $this->data
        ];
    }
}
