<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ThumbsVideoProfileResource extends JsonResource
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
            $thumbs  = Image::make($patch )->resize(200, 150)->encode('data-url', 50)->encoded;
        } else {
            $patch = storage_path('app/public/') . '/social/video_aux.png';
            $thumbs  = Image::make($patch )->resize(200, 150)->encode('data-url', 50)->encoded;
        }
        return [
            'id' => $this->id,
            'cron' => Str::uuid(),
            'moment' => Carbon::parse($this->moment)->format('d-m-Y H:i'),
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' =>  $this->subtitle,
            'rating' => $this->rating,
            'thumbs' => $thumbs,
            'note' => $this->note,
            'status' => (bool) $this->status_id,
            'in_history' => (bool) $this->in_history,
            'history_id' =>  $this->history_id,
            'type' => 1
        ];
    }
}
