<?php

namespace App\Http\Resources;

use App\Models\PhotoComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
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

        $sub = $this->user_uid === Auth::user()->uid ? 'Publicastes esta imagen '. Carbon::parse($this->moment)->diffForHumans()
            :  $user->full_names . ' publico esta imagen '. Carbon::parse($this->moment)->diffForHumans();

        $commes = PhotoComment::query()->where('photo_id',  $this->id)->orderBy('moment', 'desc')->get();

        $resulComments = CommentsResource::collection($commes);

        return [
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'id' => $this->id,
            'moment' => $sub, //(int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'rating' => $this->rating,
            'comments' => $resulComments,
            'note' => $this->note,
            'type' => 1,
            'url'=> Image::make(storage_path('app/public/') . $this->user_uid . '/photos/' . $this->url)->encode('data-url', 70)->encoded,
        ];
    }
}
