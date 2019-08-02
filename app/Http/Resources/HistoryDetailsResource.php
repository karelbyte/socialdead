<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if  ($this->type === 1) { $item = new PhotoResource($this->photo);}
        if  ($this->type === 2) { $item = new VideoResource($this->video);}
        if  ($this->type === 3) {  $item = new AudioResource($this->audio);}

        return [
            'id' => $this->id,
            'type' => $this->type,
            'item' => $item,
            'status_id' =>  $this->status_id
        ];
    }
}
