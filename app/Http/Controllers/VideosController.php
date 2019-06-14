<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\Models\Video;
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
        return  VideoResource::collection( $data);
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
                Video::query()->create([
                    'user_uid' => $uid,
                    'moment' => Carbon::now(),
                    'url' =>  $name,
                    'title' =>  'sin titulo',
                    'subtitle' =>  'sin subtitulo'
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
        Video::query()->where('id', $request->id)->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'status_id' => $request->status_id,
        ]);
        return http_response_code(200);

    }
}
