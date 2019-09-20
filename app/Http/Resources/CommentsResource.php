<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class CommentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::query()->find($this->from_user);

        $avatar = $user->avatar === null ? Image::make($this->symbol($this->birthdate)['url'])->encode('data-url')
            : Image::make(storage_path('app/public/') . $user->uid . '/profile/avatar/' . $user->avatar)->resize(150, 150)->encode('data-url', 50);

        return [
            'id' => $this->id,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'note' => $this->note,
            'name' => $user->full_names,
            'avatar'=> $avatar->encoded
        ];
    }
}
