<?php

namespace App\Http\Controllers;

use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PhotosController extends Controller
{
    public function getPhotosLists(Request $request) {
        $data = $request->user()->photos;
        return  PhotoResource::collection( $data);
    }

    public function savePhoto(Request $request) {
        try {
            $uid = $request->user()->uid;
            $file = $request->file;
            $ext = strtoupper($file->getClientOriginalExtension());
            $name = Carbon::now()->timestamp . '.'.$ext;
            if ($ext === 'JPG' || $ext === 'JPEG' || $ext === 'PNG') {
                $patch = storage_path('app/public/') . $uid .'/photos';
                File::exists( $patch) or File::makeDirectory($patch , 0777, true, true);
                $request->file->storeAs('public/'.$uid .'/photos/', $name);
                Photo::query()->create([
                    'user_uid' => $uid,
                    'moment' => Carbon::now(),
                    'url' =>  $name,
                    'title' =>  'sin titulo',
                    'subtitle' =>  'sin subtitulo'
                ]);
                return response()->json('Se archivo la imagen!');
            } else {
                return response()->json('El archivo no esta permitido', 500);
            }

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function destroyPhoto($id) {
        $photo = Photo::query()->find($id);
        $photo->photoEraser();
        return http_response_code(200);
    }

    public function updatePhoto (Request $request) {
        Photo::query()->where('id', $request->id)->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'status_id' => $request->status_id,
        ]);
        return http_response_code(200);

    }
}
