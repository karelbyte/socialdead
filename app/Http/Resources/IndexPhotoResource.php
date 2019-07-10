<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
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

        return [
            'cron' => Carbon::now()->timestamp + $this->id,
            'user' => new UserSearch(User::query()->find($this->user_uid)),
            'id' => $this->id,
            'moment' => (int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' =>  $this->subtitle,
            'rating' => $this->rating,
            'type' => 1, // IMAGENES
            'url'=> Image::make(storage_path('app/public/') . $this->user_uid . '/photos/' . $this->url)->encode('data-url', 50)->encoded,
        ];
    }
}
