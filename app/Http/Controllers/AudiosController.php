<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudioResource;
use App\Http\Resources\ThumbsAudioResource;
use App\Models\Audio;
use App\Models\AudioShare;
use App\Models\History;
use App\Models\HistoryDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AudiosController extends Controller
{

    public function getAudiosLists(Request $request) {
        $data = $request->user()->audios;
        return  ThumbsAudioResource::collection( $data);
    }

    public function getAudio($id) {
        $data = Audio::query()->find($id);
        return new AudioResource($data);
    }

    public function saveAudio(Request $request) {
        try {
            $uid = $request->user()->uid;
            $file = $request->file;
            $ext = strtoupper($file->getClientOriginalExtension());
            $name = Carbon::now()->timestamp . '.' . $ext;
            $str = strlen($name);
            $pureName = substr($name, 0,  $str-4);
            if ( $ext === 'MP3' || $ext === 'M4A' ||   $ext === 'AMR') {
                $patch = storage_path('app/public/') . $uid .'/audios';
                File::exists( $patch) or File::makeDirectory($patch , 0777, true, true);
                $request->file->storeAs('public/'.$uid .'/audios/', $name);
                if ($ext !== 'MP3' ) {
                    \FFMpeg::fromDisk('public')
                        ->open($uid .'/audios/' . $name)
                        ->export()
                        ->inFormat(new \FFMpeg\Format\Audio\Mp3())
                        ->toDisk('public')
                        ->save($uid .'/audios/' . $pureName . '.mp3');
                    $patch =  $uid .'/audios/'. $name;
                    Storage::disk('public')->delete($patch);
                    $name =  $pureName . '.mp3';
                }
                Audio::query()->create([
                    'user_uid' => $uid,
                    'moment' => Carbon::now(),
                    'url' =>  $name,
                    'title' => 'sin titulo',
                    'subtitle' => 'sin subtitulo'
                ]);
                return response()->json('Se archivo el audio!');
            } else {
                return response()->json('El archivo no esta permitido', 500);
            }

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function destroyAudio($id) {
        $audio = Audio::query()->find($id);
        $audio->audioEraser();
        return http_response_code(200);
    }

    public function updateAudio (Request $request) {

        if ($request->in_history ) {
            if ($request->history_id === 0  ||  $request->history_id === null)
            {
                $newHistory = History::query()->create([
                    'user_id' => $request->user()->uid,
                    'type' => 5, // AUDIO
                    'moment' => Carbon::now(),
                    'title' => $request->title,
                    'subtitle' => $request->subtitle,
                    'status_id' => 1
                ]);
                $det = $newHistory->details()->create([
                    'type' => 3, // AUDIO
                    'item' => $request->id,
                    'status_id' => 1
                ]);
            }
        } else {
            HistoryDetails::query()->where('id', $request->history_id)->delete();
        }
        Audio::query()->where('id', $request->id)->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'status_id' => $request->status_id,
            'in_history' => $request->in_history,
            'history_id' => $request->in_history ? $det->id : 0
        ]);

        return http_response_code(200);

    }

    public function shareAudio(Request $request) {
        foreach ($request->sharelist as $userUid ) {
            AudioShare::query()->create([
                'audio_id' => $request->item_id,
                'to_user' => $userUid,
                'from_user' =>  $request->user()->uid,
                'moment' => Carbon::now()
            ]);
        }
        return http_response_code(200);
    }
}
