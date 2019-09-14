<?php

namespace App\Http\Resources;

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

        $user = User::query()->find( $this->from_user);

        return [
            'id' => $this->id,
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'url'=> Image::make(storage_path('app/public/') . $this->from_user . '/photos/' . $this->photo->url)->encode('data-url', 70)->encoded,
            'moment' => Carbon::parse($this->moment)->format('d-m-Y H:i'),
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->photo->title,
            'subtitle' => $user->full_names . ' te compartio esta  imagen  '. Carbon::parse($this->moment)->diffForHumans(),
            'rating' => $this->photo->rating,
            'type' => 1, // IMAGENES
        ];
    }
}
