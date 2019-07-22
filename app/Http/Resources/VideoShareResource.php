<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class VideoShareResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $str = strlen($this->video->url);
        $pureName = substr($this->video->url, 0,  $str-4);
        $patch = storage_path('app/public/') . $this->from_user. '/videos/' . $pureName . '.png';
        if (file_exists(storage_path('app/public/') . $this->from_user . '/videos/' . $pureName . '.png')) {
            $thumbs  = Image::make($patch )->encode('data-url', 50)->encoded;
        } else {
            $patch = storage_path('app/public/') . '/social/video_aux.png';
            $thumbs  = Image::make($patch )->encode('data-url', 50)->encoded;
        }
        $user = User::query()->find($this->from_user);

        return [
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'id' => $this->id,
            'moment' => (int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' => $user->full_names . ' te compartio este video '. Carbon::parse($this->moment)->diffForHumans(),
            'rating' => $this->rating,
            'thumbs' => $thumbs,
            'type' => 2, // VIDEO
        ];
    }
}
