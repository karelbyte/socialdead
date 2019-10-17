<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\UserStore;
use App\Traits\UserFileStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FilesController extends Controller
{
    use UserFileStore;

    public function delete(Request $request) {

        $patch = $request->user()->uid .'/'. $request->type .'/'.$request->name;

        $getSize = Storage::disk('public')->size($patch);

        $size = round(($getSize / 1048576.2), 4);

        $this->restStore($request->user()->uid, $size);

        Storage::disk('public')->delete($patch);

        return response()->json('Archivo eliminado con exito!');
    }

    public function upStore(Request $request) {
       $store = UserStore::query()->where('user_uid', $request->user()->uid)->first();
       $store->gigas += $request->up;
       $store->save();
       // FALTARIA ACTULIZAR UN HISTORIAL DE PAGOS
       http_response_code(200);
    }

    public function getFile (Request $request) {
        $url = '';
        switch ($request->type) {
            case 'videos':
                $file = storage_path('app/public/') . $request->user()->uid . '/videos/' . $request->name;
                $data = base64_encode(file_get_contents($file));
                $url =  'data:video/mp4;base64,' .$data;
                break;
            case 'audios':
                $file = storage_path('app/public/') . $request->user()->uid . '/audios/' . $request->name;
                $data = base64_encode(file_get_contents($file));
                $url = 'data:audio/mp3;base64,'.$data;
                break;
            case 'photos':
                $img = Image::make(storage_path('app/public/') . $request->user()->uid . '/photos/' . $request->name);
                $url = $img->encode('data-url', 70)->encoded;
                break;
        }
      return response()->json($url);
    }

    public function getAllFiles (Request $request) {
        $folders = ['audios', 'videos', 'photos', 'files'];
        $files = [];
        $fileCant = 0;
        $totalSize = 0;
        $store = UserStore::query()->where('user_uid', $request->user()->uid)->first();

        foreach ($folders as $folder) {
            $filesStore = Storage::disk('public')->files( $request->user()->uid . '/'. $folder);
            foreach ($filesStore as $file) {
                $filename = explode('/', $file)[2];
                $xe = substr($filename, 0, 1);
                $size = round(((Storage::disk('public')->size($file) /  1048576.2)), 4);
                if ($xe != 'T') {
                    $files [] = [
                        'cron' => Str::uuid(),
                        'name' => explode('/', $file)[2],
                        'date' =>  date ("d-m-Y H:i:s", filemtime( storage_path('app/public/'). $file)),
                        'size' => $size . ' MB',
                        'type' => $folder
                    ];
                    $totalSize += $size;
                    $fileCant ++;
                }

            }
        }
        $pie = [
            ['y' =>round($totalSize, 2) , 'name' => 'Usado', 'color' => 'red'],
            ['y' => ($store->gigas * 1000) - round($totalSize, 2) , 'name' => 'Libre', 'color' => 'green']
        ];

        $data = [
            'files' => $files,
            'cant' => $fileCant,
            'size' => round($totalSize, 2) . ' MB',
            'pie' => $pie,
            'gigas' => $store->gigas
        ];
        return response()->json($data);
    }
}
