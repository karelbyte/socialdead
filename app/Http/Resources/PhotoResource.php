<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class PhotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $img = Image::make(storage_path('app/public/') . $this->user_uid . '/photos/' . $this->url);
        return [
            'id' => $this->id,
            'moment' => Carbon::parse($this->moment)->format('d-m-Y H:i'),
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' =>  $this->subtitle,
            'rating' => $this->rating,
            'url'=> $img->encode('data-url', 70)->encoded,
            'H' => $img->height(),
            'W' => $img->width(),
            'note' => $this->note,
            'in_history' => (bool) $this->in_history,
            'history_id' =>  $this->history_id,
            'status' => (bool) $this->status_id
        ];
    }
}
