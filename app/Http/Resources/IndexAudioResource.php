<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class IndexAudioResource extends JsonResource
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
        $user = User::query()->find($this->uid);

        return [
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'id' => $this->id,
            'moment' => (int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' => $user->full_names . ' publico este audio '. Carbon::parse($this->moment)->diffForHumans(),
            'rating' => $this->rating,
            'thumbs' => $thumbs,
            'type' => 3, // AUDIO
        ];
    }
}
