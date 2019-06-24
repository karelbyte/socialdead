<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->type === 'text')  $da = [ 'text'=> $this->msj ];
        if ($this->type === 'emoji')  [ $da = [ 'emoji'=> $this->msj ]];
         return [
            'id' => $this->id,
            'type' => $this->type,
            'author' => $this->user_uid,
            'data' => $da,
            'status_id' =>  $this->status_id,
            'moment' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
