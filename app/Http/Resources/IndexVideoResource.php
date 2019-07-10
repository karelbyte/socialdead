<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class IndexVideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $uri =  'data:video/mp4;base64,' . base64_encode(file_get_contents(storage_path('app/public/') . $this->user_uid . '/videos/' . $this->url));
        return [
            'cron' => Carbon::now()->timestamp + $this->id,
            'user' => new UserSearch(User::query()->find($this->uid)),
            'id' => $this->id,
            'moment' => (int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' =>  $this->subtitle,
            'rating' => $this->rating,
            'url'=> $uri,
            'type' => 2, // VIDEO
        ];
    }
}
