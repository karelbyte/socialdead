<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class IndexPhotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::query()->find($this->user_uid);
        return [
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'id' => $this->id,
            'moment' => (int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' => $user->full_names . ' publico esta  imagen  '. Carbon::parse($this->moment)->diffForHumans(),
            'rating' => $this->rating,
            'type' => 1, // IMAGENES
            'url'=> Image::make(storage_path('app/public/') . $this->user_uid . '/photos/' . $this->url)->encode('data-url', 50)->encoded,
        ];
    }
}
