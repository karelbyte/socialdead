<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Http\Resources\IndexPhotoResource;
use App\Http\Resources\IndexVideoResource;
use App\Http\Resources\PhotoResource;
use App\Http\Resources\PhotoShareResource;
use App\Http\Resources\ThumbsPhotoResource;
use App\Http\Resources\ThumbsVideoResource;
use App\Http\Resources\UserProfileGeneral;
use App\Http\Resources\VideoResource;
use App\Http\Resources\VideoShareResource;
use App\Models\Contact;
use App\Models\Photo;
use App\Models\PhotoShare;
use App\Models\User;
use App\Models\VideoShare;
use Illuminate\Database\Eloquent\Collection;
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

        $contacts = Contact::query()->join('users', 'users.uid','contacts.contact_user_uid')
            ->where('contacts.user_uid', $request->user()->uid)
            ->take(6)->get();

        $data = [
            'profile' => new UserProfileGeneral($request->user()),
            'hobbies' =>  $Hobb,
            'photos' => ThumbsPhotoResource::collection( $photos ),
            'videos' => ThumbsVideoResource::collection( $videos ),
            'contacts' => ContactResource::collection($contacts),
        ];
        return $data;
    }

    public function getWall (Request $request) {

        $data = new Collection();

        // FOTOS Y VIDEOS PUBLICADOS POR TUS CONTACTOS
        $photos = Contact::query()->leftJoin('photos', 'contacts.contact_user_uid', 'photos.user_uid')
            ->where('contacts.user_uid', $request->user()->uid)
            ->whereRaw('datediff(now(), photos.moment) <=10')
            ->select( 'contacts.contact_user_uid as uid', 'photos.*')
            ->orderBy( 'photos.moment', 'desc')
            ->get();
        foreach ($photos as $photo) {
            $data->push(new IndexPhotoResource($photo));
        }

        $videos = Contact::query()->leftJoin('videos', 'contacts.contact_user_uid', 'videos.user_uid')
            ->where('contacts.user_uid', $request->user()->uid)
            ->whereRaw('datediff(now(), videos.moment) <=10')
            ->select( 'contacts.contact_user_uid as uid', 'videos.*')
            ->orderBy( 'videos.moment', 'desc')
            ->get();

        foreach ($videos as $video) {
            $data->push(new IndexVideoResource($video));
        }

        // FOTOS Y VIDEOS COMPARTIDOS PARA TI POR TUS CONTACTOS

        $photos_share = PhotoShare::query()->with('user', 'photo')
            ->where('to_user', $request->user()->uid)
            ->whereRaw('datediff(now(), photos_shares.moment) <= 3')
            ->select( '*')
            ->orderBy( 'photos_shares.moment', 'desc')
            ->get();

        foreach ($photos_share as $photo_share) {
            $data->push(new PhotoShareResource($photo_share));
        }

        $videos_share = VideoShare::query()->with('user', 'video')
            ->where('to_user', $request->user()->uid)
            ->whereRaw('datediff(now(), videos_shares.moment) <= 3')
            ->select( '*')
            ->orderBy( 'videos_shares.moment', 'desc')
            ->get();

        foreach ($videos_share as $video_share) {
            $data->push(new VideoShareResource($video_share));
        }

        // ORGANIZANDO
        $sorted = $data->sortByDesc('moment');

        return $sorted->values()->all();
    }
}
