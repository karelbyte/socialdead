<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentsResource;
use App\Http\Resources\PhotoCommentsResource;
use App\Http\Resources\PhotoResource;
use App\Models\History;
use App\Models\HistoryDetails;
use App\Models\Photo;
use App\Models\PhotoComment;
use App\Models\PhotoShare;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PhotosController extends Controller
{
    public function getPhotosLists(Request $request) {
        $data = $request->user()->photos;
        return  PhotoResource::collection( $data);
    }

    public function getPhoto($id) {
        $data = Photo::query()->find($id);
        return new PhotoResource($data);
    }

    public function setComment(Request $request) {
       PhotoComment::query()->create([
            'from_user' => $request->user()->uid,
            'photo_id' => $request->id,
            'note' => $request->note,
            'moment' => Carbon::now(),
        ]);
       $comments = PhotoComment::query()->where('photo_id', $request->id)
           ->orderBy('moment', 'desc')->get();

        return CommentsResource::collection($comments);
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
                $photo = Photo::query()->create([
                    'user_uid' => $uid,
                    'moment' => Carbon::now(),
                    'url' =>  $name,
                    'title' => $request->has('title') ? $request->title : 'sin titulo',
                    'subtitle' => $request->has('subtitle') ? $request->subtitle :  'sin subtitulo',
                    'status_id' => $request->has('status') ? 1 : 0,
                    'note' => $request->note
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

    public function sharePhoto(Request $request) {
        foreach ($request->sharelist as $userUid ) {
            PhotoShare::query()->create([
                'photo_id' => $request->item_id,
                'to_user' => $userUid,
                'from_user' =>  $request->user()->uid,
                'moment' => Carbon::now()
            ]);
        }
        return http_response_code(200);
    }

    public function updatePhoto (Request $request) {
        $historyID = 0;
        if ($request->in_history ) {
            if ($request->history_id === 0  ||  $request->history_id === null)
            {
                $newHistory = History::query()->create([
                    'user_id' => $request->user()->uid,
                    'type' => 1, // FOTO
                    'moment' => Carbon::now(),
                    'title' => $request->title,
                    'subtitle' => $request->subtitle,
                    'status_id' => 1
                ]);
                $det = $newHistory->details()->create([
                    'type' => 1, // FOTO
                    'item' => $request->id,
                    'status_id' => 1
                ]);
                $historyID = $det->id;
            }
        } else {
            HistoryDetails::query()->where('id', $request->history_id)->delete();
        }
        Photo::query()->where('id', $request->id)->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'status_id' => $request->status_id,
            'in_history' => $request->in_history,
            'moment' => Carbon::now(),
            'note' => $request->note,
            'history_id' => $historyID
        ]);
        return http_response_code(200);
    }
}
