<?php

namespace App\Http\Resources;

use App\Models\AudioComment;
use App\Models\User;
use App\Models\VideoComment;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
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

        $sub = $this->user_uid === Auth::user()->uid ? 'Publicastes este audio '. Carbon::parse($this->moment)->diffForHumans()
            :  $user->full_names . ' publico este audio '. Carbon::parse($this->moment)->diffForHumans();

        $commes = AudioComment::query()->where('audio_id',  $this->id)->orderBy('moment', 'desc')->get();

        $resulComments = CommentsResource::collection($commes);

        return [
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'id' => $this->id,
            'moment' => $sub, // (int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' => $this->subtitle, // $user->full_names . ' publico este audio '. Carbon::parse($this->moment)->diffForHumans(),
            'rating' => $this->rating,
            'thumbs' => $thumbs,
            'comments' => $resulComments,
            'type' => 3, // AUDIO
        ];
    }
}
