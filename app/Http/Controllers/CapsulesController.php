<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Http\Resources\CapsuleUserResource;
use App\Http\Resources\Notify;
use App\Http\Resources\ReminderResource;
use App\Jobs\SendEmailJob;
use App\Mail\NotificationConstableCapsule;
use App\Mail\UserNotification;
use App\Mail\UserNotificationRecurrent;
use App\Models\Capsule;
use App\Models\Notification;
use App\Models\Reminder;
use App\Models\User;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CapsulesController extends Controller
{
    public function getList(Request $request) {
        $data = Capsule::query()->with('medias', 'constables', 'shares', 'emails', 'photos')
            ->where('user_uid', $request->user()->uid)
            ->orderBy('activate', 'desc')
            ->orderBy('opendate', 'asc')
            ->get();
        //return $data;
        return  CapsuleUserResource::collection($data);
    }

    public function save(Request $request) {
        
        $item = $request->all();

        $securitys = $item['securitys'] === 'true';

        $capsule = Capsule::query()
            ->create([
                'user_uid' => $request->user()->uid,
                'moment' => Carbon::now(),
                'opendate' => date('Y-m-d', strtotime($item['moment'])),
                'title' => $item['title'],
                'subtitle' => $item['subtitle'],
                'note' => $item['note'],
                'recurrent' => (bool) $item['recurrent'],
                'activate' => $item['activate'],
                'securitys' => $securitys,
                'iv' => Str::random(16)//openssl_random_pseudo_bytes(16)
            ]);

        // CONSTABLES
        if ( $securitys) {

            $key = Carbon::now()->timestamp . strtoupper(Str::random(5));
            $capsule->constables()->create([
                'user_uid' => $item['constable1'],
                'key' => $key
            ]);

            if (array_key_exists('constable2',  $item) && $item['constable2'] !== null && $item['constable2'] !== '') {
                $key1 = strtoupper(Str::random(5)) .Carbon::now()->timestamp;
                $capsule->constables()->create([
                    'user_uid' => $item['constable2'],
                    'key' => $key1
                ]);
                $key .= $key1;
            }
        }

        // IMAGENES
        if (array_key_exists('images',  $item) ) {
            foreach ( $item['images'] as $image) {
                $capsule->details()->create([
                    'type' => 3,
                    'item_id' => $image
                ]);
            }
        }

        // MEDIAS
        if (array_key_exists('medias',  $item) ) {
            foreach ($item['medias'] as $media) {
                $capsule->details()->create([
                    'type' => $media['type'],
                    'item_id' => $media['id']
                ]);
            }
        }

        // EMAIL DESTINATARIOS
        if (array_key_exists('emails',  $item) ) {
            foreach ($item['emails'] as $email) {
                if ($email['value'] !== null) {
                    $capsule->emails()->create([
                        'email' => $email['value'],
                        'token' => Str::uuid()->toString() . Carbon::now()->timestamp,
                        'status_id' => 1
                    ]);
                }
            }
        }

        // USUARIOS DEL SOCIAL
        if (array_key_exists('users',  $item) ) {
            foreach ($item['users'] as $userUid ) {
                $capsule->shares()->create([
                    'to_user' => $userUid,
                ]);
            }
        }

        if (array_key_exists('files_cant',  $item) && (int) $item['files_cant'] > 0 ) {
            for ($i = 0; $i <=  (int) $item['files_cant']  -  1; $i++) {
                $patch = $request->user()->uid .'/capsules/capsule'. $capsule->id;
                if ((bool)$item['activate'] &&  $securitys) {
                    $conten = file_get_contents( $item['file'.$i]);
                    $cyfer =  openssl_encrypt($conten, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $capsule->iv);
                    $subname = strtoupper(str_replace( ' ', '', $item['file'.$i]->getClientOriginalName()));
                    Storage::disk('public')->put($patch. '/C-'. $subname, $cyfer);
                } else {
                    $subname = strtoupper(str_replace( ' ', '', $item['file'.$i]->getClientOriginalName()));
                    $item['file'.$i]->storeAs('public/'. $patch, $subname);
                }
            }
        }

        // SI SE CREA Y ACTIVA AL UNISONO
        if  ($item['activate'] &&  $securitys ) {
            // NOTIFICANDO A ALGUACEAS
            $constables =  $capsule->constables->pluck('user_uid');

            $constable1 = isset($constables[0]) ? User::query()->find($constables[0]) : false;

            $constable2 = isset($constables[1]) ?  User::query()->find($constables[1]) : false;

            if ($constable1 !== false) {

                $info = 'Ha creado un capsula de la cual te a nombrado albacea, esta se abrira ' .
                    Carbon::parse($capsule->opendate)->diffForHumans(null, false, false, 2)
                    . ' le notificaremos con instrucciones de apertura en su momento.';


                $data_email = [
                    'from' => $request->user()->full_names,
                    'to' => $constable1->full_names,
                    'note' => $info,
                    'url_to_response' => 'http://socialdead.es/#/capules'
                ];

                $data = Notification::query()->create([
                    'type_id' => 6,  // NOTIFICACION DE ALBACEA
                    'moment' => Carbon::now(),
                    'from_user' => $request->user()->uid,
                    'to_user' => $constable1->uid,
                    'note' =>  $info,
                    'status_id' => 1 // NO VISTO
                ]);

                SendEmailJob::dispatch($constable1->email, new NotificationConstableCapsule($data_email))->onConnection('mails');

                broadcast(new NotificationEvent($constable1->uid, new Notify($data)))->toOthers();
            }

            if ($constable2 !== false) {

                $info = 'Ha creado un capsula de la cual te a nombrado albacea, esta se abrira ' .
                    Carbon::parse($capsule->opendate)->diffForHumans(null, false, false, 2)
                    . ' le notificaremos con instrucciones de apertura en su momento.';


                $data_email = [
                    'from' => $request->user()->full_names,
                    'to' => $constable2->full_names,
                    'note' => $info,
                    'url_to_response' => 'http://socialdead.es/#/capules'
                ];

                $data = Notification::query()->create([
                    'type_id' => 6,  // NOTIFICACION DE ALBACEA
                    'moment' => Carbon::now(),
                    'from_user' => $request->user()->uid,
                    'to_user' => $constable2->uid,
                    'note' =>  $info,
                    'status_id' => 1 // NO VISTO
                ]);

                SendEmailJob::dispatch($constable2->email, new NotificationConstableCapsule($data_email))->onConnection('mails');

                broadcast(new NotificationEvent($constable2->uid, new Notify($data)))->toOthers();
            }
        }

        return  $capsule;
    }

    public function update(Request $request) {

        $item = $request->all();

        $capsule = Capsule::query()->find($item['id']);

        $securitys = $item['securitys'] === 'true';

        // ALGUACEAS

        if ( $securitys) {

            $key = Carbon::now()->timestamp . strtoupper(Str::random(5));
            $capsule->constables()->create([
                'user_uid' => $item['constable1'],
                'key' => $key
            ]);

            if (array_key_exists('constable2',  $item) && $item['constable2'] !== null && $item['constable2'] !== '') {
                $key1 = strtoupper(Str::random(5)) .Carbon::now()->timestamp;
                $capsule->constables()->create([
                    'user_uid' => $item['constable2'],
                    'key' => $key1
                ]);
                $key .= $key1;
            }
        }


        // IMAGENES
        $capsule->details()->where('type', 3)->delete();
        if (array_key_exists('images',  $item) ) {
            foreach ( $item['images'] as $image) {
                $capsule->details()->create([
                    'type' => 3,
                    'item_id' => $image
                ]);
            }
        }

        // MEDIAS
        $capsule->details()->whereIn('type', [1, 2])->delete();
        if (array_key_exists('medias',  $item) ) {
            foreach ($item['medias'] as $media) {
                $capsule->details()->create([
                    'type' => $media['type'],
                    'item_id' => $media['id']
                ]);
            }
        }

        // CORREOS
        $capsule->emails()->delete();
        if (array_key_exists('emails',  $item) ) {
            foreach ($item['emails'] as $email) {
                if ($email['value'] !== null) {
                    $capsule->emails()->create([
                        'email' => $email['value'],
                        'token' => Str::uuid()->toString() . Carbon::now()->timestamp,
                        'status_id' => 1
                    ]);
                }
            }
        }

        // USERS
        $capsule->shares()->delete();
        if (array_key_exists('users',  $item) ) {
            foreach ($item['users'] as $userUid ) {
                $capsule->shares()->create([
                    'to_user' => $userUid,
                ]);
            }
        }


       // ELIMINANDO FICHEROS QUE NO SE VAN A EMACAPSULAR
        if (array_key_exists('file_change',  $item) ) {
            foreach ($item['file_change'] as $file ) {
               if ($file['type'] === 'delete') {
                   $patch = $request->user()->uid .'/capsules/capsule' .$request->id . '/' .$file['name'] ;
                   Storage::disk('public')->delete( $patch);
               }
            }
        }

        $openDate = Carbon::parse(date('Y-m-d', strtotime($item['moment'])));

        $now = Carbon::now();

        $openDate_IsOK = $now->greaterThan($openDate);

        Capsule::query()
            ->where('id', $item['id'])
            ->update([
                'user_uid' => $request->user()->uid,
                'moment' => Carbon::now(),
                'opendate' => date('Y-m-d', strtotime($item['moment'])), // COMPROBAR QUE LA FECHA ES VALIDA
                'title' => $item['title'],
                'subtitle' => $item['subtitle'],
                'note' => $item['note'],
                'recurrent' => (bool) $item['recurrent'],
                'activate' => !$openDate_IsOK ? $item['activate']: 0,
                'securitys' =>  $securitys,
            ]);

        if ($item['activate'] &&  $securitys) {
            // NOTIFICANDO A ALGUACEAS

            $constables = $capsule->constables->pluck('user_uid');

            $constable1 = isset($constables[0]) ? User::query()->find($constables[0]) : false;

            $constable2 = isset($constables[1]) ?  User::query()->find($constables[1]) : false;

            if ($constable1 !== false) {

                $info = 'Ha creado un capsula de la cual te a nombrado albacea, esta se abrira ' .
                    Carbon::parse($capsule->opendate)->diffForHumans(null, false, false, 2)
                    . ' le notificaremos con instrucciones de apertura en su momento.';


                $data_email = [
                    'from' => $request->user()->full_names,
                    'to' => $constable1->full_names,
                    'note' => $info,
                    'url_to_response' => 'http://socialdead.es/#/capules'
                ];

                $data = Notification::query()->create([
                    'type_id' => 6,  // NOTIFICACION DE ALBACEA
                    'moment' => Carbon::now(),
                    'from_user' => $request->user()->uid,
                    'to_user' => $constable1->uid,
                    'note' =>  $info,
                    'status_id' => 1 // NO VISTO
                ]);

                SendEmailJob::dispatch($constable1->email, new NotificationConstableCapsule($data_email))->onConnection('mails');

                broadcast(new NotificationEvent($constable1->uid, new Notify($data)))->toOthers();
            }

            if ($constable2 !== false) {

                $info = 'Ha creado un capsula de la cual te a nombrado albacea, esta se abrira ' .
                    Carbon::parse($capsule->opendate)->diffForHumans(null, false, false, 2)
                    . ' le notificaremos con instrucciones de apertura en su momento.';


                $data_email = [
                    'from' => $request->user()->full_names,
                    'to' => $constable2->full_names,
                    'note' => $info,
                    'url_to_response' => 'http://socialdead.es/#/capules'
                ];

                $data = Notification::query()->create([
                    'type_id' => 6,  // NOTIFICACION DE ALBACEA
                    'moment' => Carbon::now(),
                    'from_user' => $request->user()->uid,
                    'to_user' => $constable2->uid,
                    'note' =>  $info,
                    'status_id' => 1 // NO VISTO
                ]);

                SendEmailJob::dispatch($constable2->email, new NotificationConstableCapsule($data_email))->onConnection('mails');

                broadcast(new NotificationEvent($constable2->uid, new Notify($data)))->toOthers();
            }
        }

        // ENCRIPTANDO FICHEROS SI SE ACTIVA LA CAPSULA
        if (!$openDate_IsOK && $item['activate'] &&  $securitys ) {

            $patchfiles = $request->user()->uid .'/capsules/capsule'. $request->id;

            $filesStore = Storage::disk('public')->files( $patchfiles);

            $key = $capsule->keyCypher();

            foreach ($filesStore as $file) {
                $patch = storage_path() . '/app/public/' .$file; // Storage::disk('public')->get($file);
                $base = $patchfiles . '/'. 'C-' . basename($patch);
                $content = file_get_contents($patch);
                $cyfer = openssl_encrypt($content, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $capsule->iv);
                Storage::disk('public')->put(  $base, $cyfer);
                Storage::disk('public')->delete($patchfiles . '/'. basename($patch));
            }
        }


        if (array_key_exists('files_cant',  $item) && (int) $item['files_cant'] > 0 ) {
            for ($i = 0; $i <=  (int) $item['files_cant']  -  1; $i++) {
                $patch = $request->user()->uid .'/capsules/capsule'. $capsule->id;
                if (!$openDate_IsOK && $item['activate'] &&  $securitys) {
                    $conten = file_get_contents( $item['file'.$i]);
                    $cyfer =  openssl_encrypt($conten, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $capsule->iv);
                    $subname = strtoupper(str_replace( ' ', '', $item['file'.$i]->getClientOriginalName()));
                    Storage::disk('public')->put($patch. '/C-'. $subname, $cyfer);
                } else {
                    $subname = strtoupper(str_replace( ' ', '', $item['file'.$i]->getClientOriginalName()));
                    $item['file'.$i]->storeAs('public/'. $patch, $subname);
                }
            }
        }


        if ($now->greaterThan($openDate)) {
            return response()->json('La fecha de apertura no puede ser menor a la fecha actual!', 500);
        }

        return  $capsule;
    }

    public function activate(Request $request) {

        $capsule =  Capsule::query()->find($request->id);

        $openDate = Carbon::parse($capsule->opendate);

        $now = Carbon::now();

        if ($now->greaterThan($openDate)) {
            return response()->json('La fecha de apertura no puede ser menor a la fecha actual!', 500);
        }

        $patchfiles = $request->user()->uid .'/capsules/capsule'. $request->id;

        $filesStore = Storage::disk('public')->files( $patchfiles);

        $key = $capsule->keyCypher();

        foreach ($filesStore as $file) {
            $patch = storage_path() . '/app/public/' .$file; // Storage::disk('public')->get($file);
            $base = $patchfiles . '/'. 'C-' . basename($patch);
            $content = file_get_contents($patch);
            $cyfer = openssl_encrypt($content, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $capsule->iv);
            Storage::disk('public')->put(  $base, $cyfer);
            Storage::disk('public')->delete($patchfiles . '/'. basename($patch));
        }

        $capsule->activate = 1;
        $capsule->save();

        // NOTIFICANDO A ALGUACEAS

        $constables =  $capsule->constables->pluck('user_uid');

        $constable1 = isset($constables[0]) ? User::query()->find($constables[0]) : false;

        $constable2 = isset($constables[1]) ?  User::query()->find($constables[1]) : false;

        if ($constable1 !== false) {

            $info = 'Ha creado un capsula de la cual te a nombrado albacea, esta se abrira ' .
                Carbon::parse($capsule->opendate)->diffForHumans(null, false, false, 2)
                . ' le notificaremos con instrucciones de apertura en su momento.';


            $data_email = [
                'from' => $request->user()->full_names,
                'to' => $constable1->full_names,
                'note' => $info,
                'url_to_response' => 'http://socialdead.es/#/capules'
            ];

            $data = Notification::query()->create([
                'type_id' => 6,  // NOTIFICACION DE ALBACEA
                'moment' => Carbon::now(),
                'from_user' => $request->user()->uid,
                'to_user' => $constable1->uid,
                'note' =>  $info,
                'status_id' => 1 // NO VISTO
            ]);

            SendEmailJob::dispatch($constable1->email, new NotificationConstableCapsule($data_email))->onConnection('mails');

            broadcast(new NotificationEvent($constable1->uid, new Notify($data)))->toOthers();
        }

        if ($constable2 !== false) {

            $info = 'Ha creado un capsula de la cual te a nombrado albacea, esta se abrira ' .
                Carbon::parse($capsule->opendate)->diffForHumans(null, false, false, 2)
                . ' le notificaremos con instrucciones de apertura en su momento.';


            $data_email = [
                'from' => $request->user()->full_names,
                'to' => $constable2->full_names,
                'note' => $info,
                'url_to_response' => 'http://socialdead.es/#/capules'
            ];

            $data = Notification::query()->create([
                'type_id' => 6,  // NOTIFICACION DE ALBACEA
                'moment' => Carbon::now(),
                'from_user' => $request->user()->uid,
                'to_user' => $constable2->uid,
                'note' =>  $info,
                'status_id' => 1 // NO VISTO
            ]);

            SendEmailJob::dispatch($constable2->email, new NotificationConstableCapsule($data_email))->onConnection('mails');

            broadcast(new NotificationEvent($constable2->uid, new Notify($data)))->toOthers();
        }

       return $key;
        // http_response_code(200);
    }


    public function authorizedOpen(Request $request) {

        $capsule = Capsule::query()->find($request->capsule_id);

        $capsule->constables()->where('user_uid', $request->user()->uid)->update([
            'authorized' => 1
        ]);

        $authorized =  $capsule->constables()->count();

        $authorized_true = $capsule->constables()->where('authorized',1)->count();

        if ($authorized === $authorized_true) {

            $patchfiles = $capsule->user_uid .'/capsules/capsule'. $request->capsule_id;

            $filesStore = Storage::disk('public')->files($patchfiles);

            $key = $capsule->keyCypher();

            foreach ($filesStore as $file) {
                $patch = storage_path() . '/app/public/' .$file;
                $base = $patchfiles . '/'. 'O-' . basename($patch);
                $content = file_get_contents($patch);
                $cyfer = openssl_decrypt($content, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $capsule->iv);
                Storage::disk('public')->put($base, $cyfer);
                Storage::disk('public')->delete($patchfiles . '/'. basename($patch));
            }

            $capsule->activate = 3; // ABIERTA
            $capsule->save();

        }

         // return  $authorized. ' = ' . $authorized_true;

        return http_response_code(200);
    }

    public function delete(Request $request) {
        $patch = $request->user()->uid .'/capsules/capsule' .$request->id;
        Storage::disk('public')->deleteDirectory($patch);
        Capsule::query()
            ->where('id', $request->id)
            ->delete();
        return http_response_code(200);
    }
}
