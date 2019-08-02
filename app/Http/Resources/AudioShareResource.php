<?php

namespace App\Http\Resources;

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

        return [
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'id' => $this->audio->id,
            'moment' => (int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' => $user->full_names . ' te compartio audio '. Carbon::parse($this->moment)->diffForHumans(),
            'rating' => $this->rating,
            'thumbs' => $thumbs,
            'type' => 3, // AUDIO
        ];
    }
}
