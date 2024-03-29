<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $uri =  'data:video/mp4;base64,' . base64_encode(file_get_contents(storage_path('app/public/') . $this->user_uid . '/videos/' . $this->url));
        return [
            'id' => $this->id,
            'url'=> $uri,
        ];
    }
}
