<?php

namespace App\Http\Resources;

use App\Models\PhotoComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PhotoShareResource extends JsonResource
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

        $commes = PhotoComment::query()->where('photo_id', $this->photo->id)->orderBy('moment', 'desc')->get();

        $resulComments = CommentsResource::collection($commes);

        return [
            'id' => $this->photo->id,
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'url'=> Image::make(storage_path('app/public/') . $this->from_user . '/photos/' . $this->photo->url)->encode('data-url', 70)->encoded,
            'moment' => $user->full_names . ' te compartio esta imagen '. Carbon::parse($this->moment)->diffForHumans(),
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->photo->title,
            'subtitle' => $this->photo->subtitle,
            'note' => $this->photo->note,
            'comments' => $resulComments,
            'rating' => $this->photo->rating,
            'type' => 1, // IMAGENES
        ];
    }
}
