<?php

namespace App\Http\Resources;

use App\Models\AudioComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AudioShareResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $patch = storage_path('app/public/') . '/social/audio_aux.jpg';

        $thumbs  = Image::make($patch )->resize(200, 150)->encode('data-url', 50)->encoded;

        $user = User::query()->find($this->from_user);

        $commes = AudioComment::query()->where('audio_id',  $this->audio->id)->orderBy('moment', 'desc')->get();

        $resulComments = CommentsResource::collection($commes);

        return [
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'id' => $this->audio->id,
            'moment' => $user->full_names . ' te compartio audio '. Carbon::parse($this->moment)->diffForHumans(), //(int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->audio->title,
            'subtitle' => $this->audio->subtitle,
            'rating' => $this->audio->rating,
            'thumbs' => $thumbs,
            'comments' => $resulComments->count() > 0 ?: [],
            'note' => $this->audio->note,
            'type' => 3, // AUDIO
        ];
    }
}
