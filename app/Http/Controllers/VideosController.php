<?php

namespace App\Http\Controllers;

use App\Http\Resources\IndexVideoResource;
use App\Http\Resources\ThumbsVideoProfileResource;
use App\Http\Resources\ThumbsVideoResource;
use App\Http\Resources\VideoResource;
use App\Models\History;
use App\Models\HistoryDetails;
use App\Models\Video;
use App\Models\VideoShare;
use Carbon\Carbon;
use FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class VideosController extends Controller
{
    public function getVideoLists(Request $request) {
        $data = $request->user()->videos;
        return  ThumbsVideoProfileResource::collection( $data);
    }

    public function getVideo($id) {
        $data = Video::query()->find($id);
        return new VideoResource($data);
    }

    public function saveVideos(Request $request) {
        try {
            $uid = $request->user()->uid;
            $file = $request->file;
            $ext = strtoupper($file->getClientOriginalExtension());
            $name = Carbon::now()->timestamp . '.' . $ext;
            $str = strlen($name);
            $pureName = substr($name, 0,  $str-4);
            if ( $ext === 'MP4' || $ext === 'MOV' ) {
                $patch = storage_path('app/public/') . $uid .'/videos';
                File::exists( $patch) or File::makeDirectory($patch , 0777, true, true);
                $request->file->storeAs('public/'.$uid .'/videos/', $name);
                if ($ext === 'MOV') {
                    FFMpeg::fromDisk('public')
                        ->open($uid .'/videos/' . $name)
                        ->export()
                        ->inFormat(new X264('libmp3lame', 'libx264'))
                        ->toDisk('public')
                        ->save($uid .'/videos/' . $pureName . '.mp4');
                    $patch =  $uid .'/videos/'. $name;
                    Storage::disk('public')->delete($patch);
                    $name =  $pureName . '.mp4';
                }
                FFMpeg::fromDisk('public')
                    ->open($uid .'/videos/' . $name)
                    ->getFrameFromSeconds(3)
                    ->export()
                    ->toDisk('public')
                    ->save($uid .'/videos/' . $pureName . '.png');
                Video::query()->create([
                    'user_uid' => $uid,
                    'moment' => Carbon::now(),
                    'url' =>  $name,
                    'title' => 'sin titulo',
                    'subtitle' => 'sin subtitulo'
                ]);
                return response()->json('Se archivo el video!');
            } else {
                return response()->json('El archivo no esta permitido', 500);
            }

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function destroyVideo($id) {
        $photo = Video::query()->find($id);
        $photo->videoEraser();
        return http_response_code(200);
    }

    public function updateVideo (Request $request) {

        if ($request->in_history ) {
            if ($request->history_id === 0  ||  $request->history_id === null)
            {
                $newHistory = History::query()->create([
                    'user_id' => $request->user()->uid,
                    'type' => 2, // FOTO
                    'moment' => Carbon::now(),
                    'title' => $request->title,
                    'subtitle' => $request->subtitle,
                    'status_id' => 1
                ]);
                $det = $newHistory->details()->create([
                    'type' => 2, // FOTO
                    'item' => $request->id,
                    'status_id' => 1
                ]);
            }
        } else {
            HistoryDetails::query()->where('id', $request->history_id)->delete();
        }
        Video::query()->where('id', $request->id)->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'status_id' => $request->status_id,
            'in_history' => $request->in_history,
            'history_id' => $request->in_history ? $det->id : 0
        ]);

        return http_response_code(200);

    }


    public function shareVideo(Request $request) {
        foreach ($request->sharelist as $userUid ) {
            VideoShare::query()->create([
                'video_id' => $request->item_id,
                'to_user' => $userUid,
                'from_user' =>  $request->user()->uid,
                'moment' => Carbon::now()
            ]);
        }
        return http_response_code(200);
    }
}
