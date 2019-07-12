<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class ThumbsPhotoResource extends JsonResource
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
            'url'=> Image::make(storage_path('app/public/') . $this->user_uid . '/photos/' . $this->url)->resize(150, 150)->encode('data-url', 50)->encoded,
        ];
    }
}
