<?php

namespace App\Http\Resources;

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
        $file = storage_path('app/public/') . $this->user_uid . '/audios/' . $this->url;
        $data = base64_encode(file_get_contents($file));
        $src = 'data: '. mime_content_type($file).';base64,'.$data;
        return [
            'id' => $this->id,
            'url'=> $src,
        ];
    }
}
