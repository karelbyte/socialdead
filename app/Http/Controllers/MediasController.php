<?php

namespace App\Http\Controllers;

use App\Http\Resources\ThumbsAudioResource;
use App\Http\Resources\ThumbsVideoProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MediasController extends Controller
{
    public function getLists(Request $request) {

        $data = new Collection();

        $dataVideo = $request->user()->videos;
        foreach ($dataVideo as $video) {
            $data->push(new ThumbsVideoProfileResource($video));
        }

        $dataAudio = $request->user()->audios;
        foreach ($dataAudio as $audio) {
            $data->push(new ThumbsAudioResource($audio));
        }

        // ORGANIZANDO
        $sorted = $data->sortByDesc('moment');

        return $sorted->values()->all();
    }
}
