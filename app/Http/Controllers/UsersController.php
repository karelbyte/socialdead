<?php

namespace App\Http\Controllers;

use App\Events\UpdateUserStatusEvent;
use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserOnly;
use App\Http\Resources\UserSearch;
use App\Mail\UserAccountConfirm;
use App\Mail\UserWelcome;
use App\Models\User;
use App\Models\UserStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use phpseclib\Crypt\Hash;

class UsersController extends Controller
{
    // ONTENIENDO EL PERFIL DEL USUARIO ACTIVO
    public function getProfile (Request $request) {
      return new UserOnly($request->user());
    }

    // ACTUALIZANDO EL ESTADO
    public function updateStatus(Request $request)
    {
        $request->user()->update(['status_id' =>  $request->status]);

        foreach ($request->user()->contacts as $contact ) {
            broadcast(new UpdateUserStatusEvent($contact->contact_user_uid))->toOthers();
        }
        return http_response_code(200);
    }

    public function confirmAcount ($secret) {
      $user = User::query()->where('secret', $secret)->first();
      if ($user === null) {
          return view('social.user_account_not found');
      } else {
          $user->email_verified_at = Carbon::now();
          $user->secret = Str::random(25);
          $user->save();
          $mail_data = [
              'user_name' => $user->full_names,
              'url' => url('/')
          ];
          Mail::to($user->email)->send(new UserWelcome($mail_data));
          return view('social.user_account_confirm_success');
      }
    }

    public function store(UserCreateRequest $request) {

        $comprobar = new \GuzzleHttp\Client();

        $re_catcha = $comprobar->post( 'https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => '6LcQxqUUAAAAAILKScU6R7RLqx0-qfySmTCE0rBA',
                'response' => $request->token
            ],
        ]);

        $result = json_decode($re_catcha->getBody()->getContents());

        if (!$result->success) return http_response_code(500);

        $data = $request->except('token');

        $data['secret'] = Str::random(30);

        $user = User::query()->create([
            'birthdate' => Carbon::parse($data['birthdate'])->format('Y-m-d'),
            'email' =>  $data['email'],
            'full_names' => $data['full_names'],
            'password' => $data['password'],
            'sex_id' => $data['sex_id'],
            'secret' => $data['secret']
        ]);

        $user->settingNotifications()->create(['notification_sound' => 1]);

        $client = new \GuzzleHttp\Client();

        $response = $client->post( config('app.url') .'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => 2,
                'client_secret' => '8VCRVWbSmmEzVyD2722xzY22EyHjdiGNFI8SjOli',
                'username' => $request->input('email'),
                'password' => $request->input('password'),
                'scope' => '',
            ],
        ]);

        $mail_data = [
            'user_name' => $user->full_names,
            'user_email' => $user->email,
            'url_confirm' => url('/'). '/confirmacion-de-cuenta/' . $user->secret
        ];

        Mail::to($request->input('email'))->send(new UserAccountConfirm($mail_data));

        $data = [
            'user' => new UserOnly($user),
            'passport' => json_decode($response->getBody(), true)
        ];
        return $data;
    }

    public function search(Request $request)
    {
        $data = User::query()
            ->where('full_names',  'LIKE', '%' . $request->search .'%')
            ->where('status_id',  '<>', UserStatus::DESCONECTADO)
            ->where('uid',  '<>', $request->user()->uid)
            ->select('*')->get();
        return UserSearch::collection($data);
    }

    public function exit(Request $request)
    {
        $request->user()->update(['status_id' => UserStatus::INACTIVO]);

        foreach ($request->user()->contacts as $contact ) {
            broadcast(new UpdateUserStatusEvent($contact->contact_user_uid))->toOthers();
        }
        return http_response_code(200);
    }

    public function updatePassword(Request $request) {
        $request->user()->update(['password' => $request->seg]);
        return http_response_code(200);
    }
}
