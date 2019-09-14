<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class IndexThinKingResource extends JsonResource
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
        $sub = $this->user_uid === Auth::user()->uid ? 'Publicastes esta nota '. Carbon::parse($this->moment)->diffForHumans()
            :  $user->full_names . ' publico esta nota '. Carbon::parse($this->moment)->diffForHumans();
        return [
            'cron' => Str::uuid(),
            'user' => new UserSearch($user),
            'id' => $this->id,
            'moment' => (int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' => $sub,
            'rating' => $this->rating,
            'type' => 5, // PENSAMIENTO
            'note' => $this->note
        ];
    }
}
