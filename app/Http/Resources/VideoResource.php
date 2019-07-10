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
        $str = strlen($this->url);
        $pureName = substr($this->url, 0,  $str-4);
        $patch = storage_path('app/public/') . $this->user_uid . '/videos/' . $pureName . '.png';
        if (file_exists(storage_path('app/public/') . $this->user_uid . '/videos/' . $pureName . '.png')) {
            $thumbs  = Image::make($patch )->encode('data-url')->encoded;
        } else {
            $patch = storage_path('app/public/') . '/social/video_aux.png';
            $thumbs  = Image::make($patch )->encode('data-url')->encoded;
        }
      //  return $patch;
        return [
            'id' => $this->id,
            'moment' => Carbon::parse($this->moment)->format('d-m-Y H:i'),
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' =>  $this->subtitle,
            'rating' => $this->rating,
            'url'=> $uri,
            'thumbs' => $thumbs,
            'status' => (bool) $this->status_id,
            'in_history' => (bool) $this->in_history,
            'history_id' =>  $this->history_id,
        ];
    }
}
