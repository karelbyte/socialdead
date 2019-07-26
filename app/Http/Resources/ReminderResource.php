<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReminderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
          //  'url'=> Image::make(storage_path('app/public/') . $this->from_user . '/photos/' . $this->photo->url)->resize(150, 150)->encode('data-url', 50)->encoded,
            'moment' => $this->moment,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' =>  $this->subtitle,
            'note' => $this->note,
            'recurrent' => (bool) $this->recurrent
        ];
    }
}
