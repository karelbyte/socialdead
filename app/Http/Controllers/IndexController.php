<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Http\Resources\PhotoResource;
use App\Http\Resources\UserProfileGeneral;
use App\Http\Resources\VideoResource;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    function getProfileData (Request $request) {
        $Hobb= $request->user()->Hobbies;
        if ( $Hobb === null) {
            $Hobb = [
                'hobby' => '',
                'music' => '',
                'tv' => '',
                'movies' => '',
                'games' => '',
                'writers' => '',
                'others' => ''
            ];
        }
        // UTIMAS 6 FOTOS
        $photos = $request->user()->photos()->orderBy('moment', 'desc')->take(6)->get();

        // UTIMAS 6 VIDEOS
        $videos = $request->user()->videos()->orderBy('moment', 'desc')->take(6)->get();

        // CONTACTOS 6
        $contacts = $request->user()->contacts()->orderBy('created_at', 'desc')->take(6)->get();

        $data = [
            'profile' => new UserProfileGeneral($request->user()),
            'hobbies' =>  $Hobb,
            'photos' => PhotoResource::collection( $photos ),
            'videos' => VideoResource::collection( $videos ),
            'contacts' => ContactResource::collection( $contacts),
        ];
        return $data;
    }
}
