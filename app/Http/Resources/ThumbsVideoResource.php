<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class ThumbsVideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $str = strlen($this->url);
        $pureName = substr($this->url, 0,  $str-4);
        $patch = storage_path('app/public/') . $this->user_uid . '/videos/T' . $pureName . '.PNG';
        if (file_exists(storage_path('app/public/') . $this->user_uid . '/videos/T' . $pureName . '.PNG')) {
            $thumbs  = Image::make($patch )->encode('data-url', 50)->encoded;
        } else {
            $patch = storage_path('app/public/') . '/social/video_aux.png';
            $thumbs  = Image::make($patch )->encode('data-url', 50)->encoded;
        }
        return [
            'id' => $this->id,
            'thumbs' => $thumbs,
        ];
    }
}
