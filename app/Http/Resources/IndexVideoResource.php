<?php

namespace App\Http\Resources;

use App\Models\PhotoComment;
use App\Models\User;
use App\Models\VideoComment;
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
        $patch = storage_path('app/public/') . $this->user_uid . '/videos/T' . $pureName . '.PNG';
        if (file_exists(storage_path('app/public/') . $this->user_uid . '/videos/T' . $pureName . '.PNG')) {
            $thumbs  = Image::make($patch )->encode('data-url', 50)->encoded;
        } else {
            $patch = storage_path('app/public/') . '/social/video_aux.png';
            $thumbs  = Image::make($patch )->encode('data-url', 50)->encoded;
        }
        $user = User::query()->find($this->uid);

        $sub = $this->uid === Auth::user()->uid ? 'Publicastes este video '. Carbon::parse($this->moment)->diffForHumans()
            :  $user->full_names . ' publico este video '. Carbon::parse($this->moment)->diffForHumans();

        $commes = VideoComment::query()->where('video_id',  $this->id)->orderBy('moment', 'desc')->get();

        $resulComments = CommentsResource::collection($commes);

        return [
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'id' => $this->id,
            'moment' => $sub, // (int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' => $this->subtitle,//  $user->full_names . ' publico este video '. Carbon::parse($this->moment)->diffForHumans(),
            'rating' => $this->rating,
            'note' => $this->note,
            'comments' => $resulComments->count() > 0 ? $resulComments : [],
            'thumbs' => $thumbs,
            'type' => 2, // VIDEO
        ];
    }
}
