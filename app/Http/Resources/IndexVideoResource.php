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
        $str = strlen($this->url);
        $pureName = substr($this->url, 0,  $str-4);
        $patch = storage_path('app/public/') . $this->user_uid . '/videos/' . $pureName . '.png';
        if (file_exists(storage_path('app/public/') . $this->user_uid . '/videos/' . $pureName . '.png')) {
            $thumbs  = Image::make($patch )->encode('data-url', 50)->encoded;
        } else {
            $patch = storage_path('app/public/') . '/social/video_aux.png';
            $thumbs  = Image::make($patch )->encode('data-url', 50)->encoded;
        }
        return [
            'cron' => Carbon::now()->timestamp + $this->id,
            'user' => new UserSearch(User::query()->find($this->uid)),
            'id' => $this->id,
            'moment' => (int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' =>  $this->subtitle,
            'rating' => $this->rating,
            'url'=>  $uri,
            'thumbs' => $thumbs,
            'type' => 2, // VIDEO
        ];
    }
}
