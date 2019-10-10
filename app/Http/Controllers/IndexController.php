<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudioShareResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\IndexAudioResource;
use App\Http\Resources\IndexPhotoResource;
use App\Http\Resources\IndexReminderResource;
use App\Http\Resources\IndexReminderShareResource;
use App\Http\Resources\IndexThinKingResource;
use App\Http\Resources\IndexVideoResource;
use App\Http\Resources\PhotoShareResource;
use App\Http\Resources\ThumbsPhotoResource;
use App\Http\Resources\ThumbsVideoResource;
use App\Http\Resources\UserProfileGeneral;
use App\Http\Resources\VideoShareResource;
use App\Models\Audio;
use App\Models\AudioShare;
use App\Models\Contact;
use App\Models\Photo;
use App\Models\PhotoShare;
use App\Models\Reminder;
use App\Models\ReminderShare;
use App\Models\Thinking;
use App\Models\Video;
use App\Models\VideoShare;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    function getProfileData (Request $request) {
        $Hobb = $request->user()->Hobbies;
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
            'photos' => ThumbsPhotoResource::collection($photos),
            'videos' => ThumbsVideoResource::collection($videos),
            'contacts' => ContactResource::collection($contacts),
        ];
        return $data;
    }

    public function getWall (Request $request) {

       $data = new Collection();

       $Photos = Photo::query()
            ->where('user_uid', $request->user()->uid)
            ->whereRaw('datediff(now(), moment) <= 10')
            ->where('photos.status_id', 1)
            ->orderBy( 'moment', 'desc')
            ->get();
        foreach ($Photos as $photo) {
            $data->push(new IndexPhotoResource($photo));
        }

        // FOTOS, VIDEOS, AUDIOS PUBLICADOS POR TUS CONTACTOS
       $photos = Contact::query()->leftJoin('photos', 'contacts.contact_user_uid', 'photos.user_uid')
            ->where('contacts.user_uid', $request->user()->uid)
            ->where('photos.status_id', 1)
            ->whereRaw('datediff(now(), photos.moment) <= 10')
            ->select( 'contacts.contact_user_uid as uid', 'photos.*')
            ->orderBy( 'photos.moment', 'desc')
            ->get();
        foreach ($photos as $photo) {
            $data->push(new IndexPhotoResource($photo));
        }

        $Videos = Video::query()
            ->where('user_uid', $request->user()->uid)
            ->whereRaw('datediff(now(), moment) <= 10')
            ->where('videos.status_id', 1)
            ->orderBy( 'moment', 'desc')
            ->select( 'videos.user_uid as uid', 'videos.*')
            ->get();
        foreach ($Videos as $Video) {
            $data->push(new IndexVideoResource($Video));
        }

        $videos = Contact::query()->leftJoin('videos', 'contacts.contact_user_uid', 'videos.user_uid')
            ->where('contacts.user_uid', $request->user()->uid)
            ->where('videos.status_id', 1)
            ->whereRaw('datediff(now(), videos.moment) <= 10')
            ->select( 'contacts.contact_user_uid as uid', 'videos.*')
            ->orderBy( 'videos.moment', 'desc')
            ->get();

        foreach ($videos as $video) {
            $data->push(new IndexVideoResource($video));
        }

        $Audios = Audio::query()
            ->where('user_uid', $request->user()->uid)
            ->whereRaw('datediff(now(), moment) <= 10')
            ->where('audios.status_id', 1)
            ->orderBy( 'moment', 'desc')
            ->select( 'audios.user_uid as uid', 'audios.*')
            ->get();
        foreach ($Audios as $Audio) {
            $data->push(new IndexAudioResource($Audio));
        }

        $audios = Contact::query()->leftJoin('audios', 'contacts.contact_user_uid', 'audios.user_uid')
            ->where('contacts.user_uid', $request->user()->uid)
            ->where('audios.status_id', 1)
            ->whereRaw('datediff(now(), audios.moment) <= 10')
            ->select( 'contacts.contact_user_uid as uid', 'audios.*')
            ->orderBy( 'audios.moment', 'desc')
            ->get();


        foreach ($audios as $audio) {
            $data->push(new IndexAudioResource($audio));
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

        $audios_share = AudioShare::query()->with('user', 'audio')
          ->where('to_user', $request->user()->uid)
          ->whereRaw('datediff(now(), audios_shares.moment) <= 3')
          ->select( '*')
          ->orderBy( 'audios_shares.moment', 'desc')
          ->get();

        foreach ($audios_share as $audio_share) {
            $data->push(new AudioShareResource($audio_share));
        }

        // BUSCANDO RECORDATORIOS
        $reminders = Reminder::query()
            ->where('user_uid', $request->user()->uid)
            ->whereDate('reminders.moment', Carbon::now())
            ->select( '*')
            ->orderBy( 'reminders.moment', 'desc')
            ->get();

        $remiders_recurrent = Reminder::query()
            ->where('user_uid', $request->user()->uid)
            ->where('recurrent', 1)
            ->whereRaw('datediff(now(), moment) = 365')->get();

        foreach ($remiders_recurrent as $reminderRecurrent) {
            $data->push(new IndexReminderResource($reminderRecurrent));
        }

        foreach ($reminders as $reminder) {
           $data->push(new IndexReminderResource($reminder));
        }

        // BUSCANDO RECORDATORIOS
        $reminders_shares_ids = ReminderShare::query()->with('reminder')
            ->leftJoin('reminders', 'reminders.id', 'reminders_shares.reminder_id')
            ->where('to_user', $request->user()->uid)
            ->whereDate('reminders.moment', Carbon::now())
            ->select( 'reminders.id')
            ->get();


        $reminders_shares = Reminder::query()
            ->whereIn('id',  $reminders_shares_ids->pluck('id'))
            ->select( '*')
            ->orderBy( 'reminders.moment', 'desc')
            ->get();

        foreach ($reminders_shares as $reminder_share) {
            $data->push(new IndexReminderShareResource($reminder_share));
        }

        // BUSCANDO PENSAMIENTOS PROPIOS
        $ThingKingsYour = Thinking::query()
            ->where('user_uid', $request->user()->uid)
            ->whereRaw('datediff(now(), moment) <= 5')
            ->orderBy( 'moment', 'desc')
            ->get();

        foreach ($ThingKingsYour as $ThingYour) {
            $data->push(new IndexThinKingResource($ThingYour));
        }

        // BUSCANDO PENSAMIENTOS DE OTROS

        $ThingKingsOthers =  Contact::query()->leftJoin('thinkings', 'contacts.contact_user_uid', 'thinkings.user_uid')
            ->where('contacts.user_uid', $request->user()->uid)
            ->whereRaw('datediff(now(), thinkings.moment) <= 10')
            ->select(  'thinkings.*')
            ->orderBy( 'thinkings.moment', 'desc')
            ->get();

        foreach ($ThingKingsOthers as $ThingYourO) {
            $data->push(new IndexThinKingResource($ThingYourO));
        }

        // ORGANIZANDO
        $sorted = $data->sortByDesc('moment');

        return $sorted->values()->all();
    }
}
