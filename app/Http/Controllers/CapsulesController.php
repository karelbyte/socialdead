<?php

namespace App\Http\Controllers;

use App\Http\Resources\CapsuleUserResource;
use App\Http\Resources\ReminderResource;
use App\Jobs\SendEmailJob;
use App\Mail\UserNotificationRecurrent;
use App\Models\Capsule;
use App\Models\Reminder;
use Carbon\Carbon;
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
            ]);

        $key = Carbon::now()->timestamp . strtoupper(Str::random(5));
        $capsule->constables()->create([
           'user_uid' => $item['constable1'],
           'key' => $key
        ]);

        if ($item['constable2'] !== null && $item['constable2'] !== '') {
            $key1 = strtoupper(Str::random(5)) .Carbon::now()->timestamp;
            $capsule->constables()->create([
                'user_uid' => $item['constable2'],
                'key' => $key1
            ]);
            $key .= $key1;
        }


        if (array_key_exists('images',  $item) ) {
            foreach ( $item['images'] as $image) {
                $capsule->details()->create([
                    'type' => 3,
                    'item_id' => $image
                ]);
            }
        }

        if (array_key_exists('medias',  $item) ) {
            foreach ($item['medias'] as $media) {
                $capsule->details()->create([
                    'type' => $media['type'],
                    'item_id' => $media['id']
                ]);
            }
        }

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
                if ($item['activate']) {
                    $conten = file_get_contents( $item['file'.$i]);
                    $cyfer =  openssl_encrypt($conten, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, openssl_random_pseudo_bytes(16));
                    $subname = strtoupper(str_replace( ' ', '', $item['file'.$i]->getClientOriginalName()));
                    Storage::disk('public')->put($patch. '/C-'. $subname, $cyfer);
                } else {
                    $subname = strtoupper(str_replace( ' ', '', $item['file'.$i]->getClientOriginalName()));
                    $item['file'.$i]->storeAs('public/'. $patch, $subname);
                }
            }

        }

        return  $capsule;
    }

    public function update(Request $request) {

        $item = $request->all();

        $capsule = Capsule::query()->find($item['id']);

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
                'activate' => $item['activate'],
            ]);

        // ALGUACEAS
        $key = Carbon::now()->timestamp . strtoupper(Str::random(5));
        $capsule->constables()->delete();
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
        // ENCRIPTANDO FICHEROS SI SE ACTIVA LA CAPSULA
        if ($item['activate']) {
            $patchfiles = $request->user()->uid .'/capsules/capsule'. $request->id;

            $filesStore = Storage::disk('public')->files( $patchfiles);

            $key = $capsule->keyCypher();

            foreach ($filesStore as $file) {
                $patch = storage_path() . '/app/public/' .$file; // Storage::disk('public')->get($file);
                $base = $patchfiles . '/'. 'C-' . basename($patch);
                $content = file_get_contents($patch);
                $cyfer = openssl_encrypt($content, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, openssl_random_pseudo_bytes(16));
                Storage::disk('public')->put(  $base, $cyfer);
                Storage::disk('public')->delete($patchfiles . '/'. basename($patch));
            }
        }


        if (array_key_exists('files_cant',  $item) && (int) $item['files_cant'] > 0 ) {
            for ($i = 0; $i <=  (int) $item['files_cant']  -  1; $i++) {
                $patch = $request->user()->uid .'/capsules/capsule'. $capsule->id;
                if ($item['activate']) {
                    $conten = file_get_contents( $item['file'.$i]);
                    $cyfer =  openssl_encrypt($conten, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, openssl_random_pseudo_bytes(16));
                    $subname = strtoupper(str_replace( ' ', '', $item['file'.$i]->getClientOriginalName()));
                    Storage::disk('public')->put($patch. '/C-'. $subname, $cyfer);
                } else {
                    $subname = strtoupper(str_replace( ' ', '', $item['file'.$i]->getClientOriginalName()));
                    $item['file'.$i]->storeAs('public/'. $patch, $subname);
                }
            }
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
            $cyfer = openssl_encrypt($content, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, openssl_random_pseudo_bytes(16));
            Storage::disk('public')->put(  $base, $cyfer);
            Storage::disk('public')->delete($patchfiles . '/'. basename($patch));
        }

        $capsule->activate = 1;
        $capsule->save();

        http_response_code(200);
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
