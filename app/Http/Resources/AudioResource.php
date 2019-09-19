<?php

namespace App\Http\Resources;

use FFMpeg\FFMpeg;
use Illuminate\Http\Resources\Json\JsonResource;

class AudioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $file = storage_path('app/public/') . $this->user->uid . '/audios/' . $this->url;
        $data = base64_encode(file_get_contents($file));
        $src = 'data:'. 'audio/mp3' .';base64,'.$data;
        return [
            'id' => $this->id,
            'url'=> $src
        ];
    }
}
