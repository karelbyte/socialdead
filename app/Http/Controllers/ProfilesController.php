<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserOnly;
use App\Http\Resources\UserProfileGeneral;
use App\Models\Politics;
use App\Models\Religion;
use App\Models\User;
use App\Models\UserJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
    //  ENVIADO PERFIL AL FRONT USER
    public function getProfile(Request $request) {
        $data = [
            'profile' => new UserProfileGeneral($request->user()),
            'religion' => Religion::all(),
            'politics' => Politics::all()
        ];
        return $data;
    }

    public function updateProfile(Request $request)
    {
      $user = User::query()->find($request->user()->uid);
      $user->update([
            'full_names' => $request->full_names,
            'email' =>$request->email,
            'phone' =>$request->phone,
            'address' =>$request->address,
            'nif' =>$request->nif,
            'birthdate' =>$request->birthdate,
            'sex_id' => $request->sex,
            'civil_status_id' => $request->civil,
            'birthplace' =>$request->birthplace,
            'country' =>$request->country,
            'who_you_are' =>$request->who_you_are,
            'website' =>$request->website,
            'facebook' =>$request->facebook,
            'twitter' =>$request->twitter,
            'religion_id' =>$request->religion['id'],
            'politics_id' =>$request->politics['id'],
            'occupation' =>$request->occupation,
        ]);

        return new UserOnly($user);
    }

    public function updateProfileAvatar(Request $request):UserOnly {

        $img = Image::make($request->img)->resize(400, 300);

        $ext = $request->file('img')->getClientOriginalExtension();

        $patch = storage_path('app/public/') .$request->user()->uid.'/profile/avatar';

        if (File::exists($patch)) {
            Storage::disk('public')->deleteDirectory($request->user()->uid.'/profile/avatar');
            File::makeDirectory($patch , 0777, true, true);
        } else {
            File::makeDirectory($patch , 0777, true, true);
        }
        $name = Carbon::now()->timestamp;
        $img->save($patch. '/'. $name. '.' . $ext , 70);

        $request->user()->update(['avatar' => '/storage/' . $request->user()->uid . '/profile/avatar/' . $name .'.' .$ext]);

        return new UserOnly($request->user());
    }


    //  ENVIADO PERFIL JOBS AL FRONT USER
    public function getProfileJobs(Request $request) {
        return  $request->user()->Jobs()->orderBy('id', 'desc')->get();
    }

    //  ENVIADO PERFIL JOBS AL FRONT USER ACTUALIZAR
    public function updateProfileJob(Request $request) {
        UserJob::query()->where('id', $request->id)
            ->update($request->all());
        return $request->user()->Jobs;
    }
    //  ENVIADO PERFIL JOBS AL FRONT USER ADD
    public function addProfileJob(Request $request) {
        $request->user()->jobs()->create($request->all());
        return  $request->user()->Jobs()->orderBy('id', 'desc')->get();
    }
    //  ENVIADO PERFIL JOBS AL FRONT USER ELIMNIAR
    public function deleteProfileJob(Request $request) {
        UserJob::query()->where('id', $request->id)
            ->delete();
        return $request->user()->Jobs;
    }

    //  ENVIADO PERFIL JOBS AL FRONT USER
    public function getProfileHobbies(Request $request) {
        $data = $request->user()->Hobbies;
        if ( $data === null) {
           $data = [
               'hobby' => '',
               'music' => '',
               'tv' => '',
               'movies' => '',
               'games' => '',
               'writers' => '',
               'others' => ''
           ];
        }
        return  $data;
    }

    //  ENVIADO PERFIL FRONT HOBBIES ADD
    public function addProfileHobbies(Request $request) {
        $request->user()->Hobbies()->delete();
        $request->user()->Hobbies()->create($request->all());
        return http_response_code(200);
    }
}
