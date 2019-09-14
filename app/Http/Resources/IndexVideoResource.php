<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
        $str = strlen($this->url);
        $pureName = substr($this->url, 0,  $str-4);
        $patch = storage_path('app/public/') . $this->user_uid . '/videos/' . $pureName . '.PNG';
        if (file_exists(storage_path('app/public/') . $this->user_uid . '/videos/' . $pureName . '.PNG')) {
            $thumbs  = Image::make($patch )->encode('data-url', 50)->encoded;
        } else {
            $patch = storage_path('app/public/') . '/social/video_aux.png';
            $thumbs  = Image::make($patch )->encode('data-url', 50)->encoded;
        }
        $user = User::query()->find($this->uid);

        $sub = $this->uid === Auth::user()->uid ? 'Publicastes este video '. Carbon::parse($this->moment)->diffForHumans()
            :  $user->full_names . ' publico este video '. Carbon::parse($this->moment)->diffForHumans();

        return [
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'id' => $this->id,
            'moment' => (int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' => $sub,//  $user->full_names . ' publico este video '. Carbon::parse($this->moment)->diffForHumans(),
            'rating' => $this->rating,
            'thumbs' => $thumbs,
            'type' => 2, // VIDEO
        ];
    }
}
