<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if  ($this->type === 1)  {
            $icon = 'photo_camera';
            $color = 'blue';
        }
        if  ($this->type === 2)  {
            $icon = 'fa fa-video';
            $color = 'red';
        }
        if  ($this->type === 3)  {
            $icon = 'fa fa-lightbulb';
            $color = 'green';
        }
        if  ($this->type === 4)  {
            $icon = 'fab fa-battle-net';
            $color= 'black';
        }
        if  ($this->type === 5)  {
            $icon = 'fa fa-microphone';
            $color= 'primary';
        }
        return [
            'id' => $this->id,
            'color' => $color,
            'icon' => $icon,
            'title' => $this->title,
            'subtitle' => $this->subtile . ' ' .  Carbon::parse($this->created_at)->format('l, j F Y h:i'),
            'details' => HistoryDetailsResource::collection($this->details),
            'status_id' =>  $this->status_id,
        ];
    }
}
