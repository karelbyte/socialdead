<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChatListResource extends JsonResource
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
        if ($this->type === 'emoji') $da = [ 'emoji'=> $this->msj ];
        if ($this->type === 'file')  {
            $file = storage_path('app/public/') . $this->user_uid . '/files/' . $this->msj;
            $data = base64_encode(file_get_contents($file));
            $src = 'data: '. mime_content_type($file).';base64,'.$data;
            $da = [
                'file' => [
                'name'=> $this->msj ,
                'url'=> $src,
                'mime' => mime_content_type($file)
                ]
             ];
        }
        $author = $this->user_uid === Auth::user()->uid ? 'me' :  $this->user_uid;
         return [
            'id' => $this->id,
            'type' => $this->type,
            'author' => $author,
            'data' => $da,
            'status_id' =>  $this->status_id,
            'moment' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
