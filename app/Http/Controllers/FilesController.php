<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FilesController extends Controller
{

    public function delete(Request $request) {
        $patch = $request->user()->uid .'/'. $request->type .'/'.$request->name;

        Storage::disk('public')->delete($patch);

        return response()->json('Archivo eliminado con exito!');
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
        foreach ($folders as $folder) {
            $filesStore = Storage::disk('public')->files( $request->user()->uid . '/'. $folder);
            foreach ($filesStore as $file) {
                $filename = explode('/', $file)[2];
                $xe = substr($filename, 0, 1);
                if ($xe != 'T') {
                    $files [] = [
                        'cron' => Str::uuid(),
                        'name' => explode('/', $file)[2],
                        'date' =>  date ("d-m-Y H:i:s", filemtime( storage_path('app/public/'). $file)),
                        'size' => round(((Storage::disk('public')->size($file) / 1024) / 1024), 4) . ' MB',
                        'type' => $folder
                    ];
                    $totalSize += ((Storage::disk('public')->size($file) / 1024) / 1024);
                    $fileCant ++;
                }

            }
        }
        $pie = [
            ['y' =>round($totalSize, 2) , 'name' => 'Usado', 'color' => 'red'],
            ['y' =>2000 - round($totalSize, 2) , 'name' => 'Libre', 'color' => 'green']
        ];

        $data = [
            'files' => $files,
            'cant' => $fileCant,
            'size' => round($totalSize, 2) . ' MB',
            'pie' => $pie
        ];
        return response()->json($data);
    }
}
