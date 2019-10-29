<?php

namespace App\Http\Resources;

use App\Models\AudioComment;
use App\Models\User;
use App\Models\VideoComment;
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
        $patch = storage_path('app/public/') . $this->from_user. '/videos/' . $pureName . '.PNG';
        if (file_exists(storage_path('app/public/') . $this->from_user . '/videos/T' . $pureName . '.PNG')) {
            $thumbs  = Image::make($patch )->encode('data-url', 50)->encoded;
        } else {
            $patch = storage_path('app/public/') . '/social/video_aux.png';
            $thumbs  = Image::make($patch )->encode('data-url', 50)->encoded;
        }
        $user = User::query()->find($this->from_user);

        $commes = VideoComment::query()->where('video_id',  $this->video->id)->orderBy('moment', 'desc')->get();

        $resulComments = CommentsResource::collection($commes);

        return [
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'id' => $this->id,
            'moment' => $user->full_names . ' te compartio este video '. Carbon::parse($this->video->moment)->diffForHumans(),
            'time_ago' => Carbon::parse($this->video->moment)->diffForHumans(),
            'title' => $this->video->title,
            'subtitle' => $this->video->subtitle,
            'rating' => $this->video->rating,
            'note' => $this->video->note,
            'thumbs' => $thumbs,
            'comments' => $resulComments->count() > 0 ?: [],
            'type' => 2, // VIDEO
        ];
    }
}
