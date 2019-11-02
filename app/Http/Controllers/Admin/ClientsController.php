<?php

namespace App\Http\Controllers\Admin;

use App\Events\ClientEraserEvent;
use App\Events\UpdateUserStatusEvent;
use App\Http\Resources\Admin\ClientResource;
use App\Models\Chat;
use App\Models\User;
use App\Models\UserStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ClientsController extends Controller
{
    // OBTINE LA LISTA DE CLIENTES Y LOS ENVIA AL FRONT PAGINADO
    public function getList(Request $request) {

        $skip = $request->input('start') * $request->input('take');

        $filters = $request->input('filters');

        $orders =$request->input('orders');

        $datos = User::query()->select('*');

        if ( $filters['value'] !== '') $datos->where( $filters['field'], 'LIKE', '%'.$filters['value'].'%');

        $datos = $datos->orderby($orders['field'], $orders['type']);

        $total = $datos->count();

        $list =  $datos->skip($skip)->take($request['take'])->get();

        $list = ClientResource::collection($list);

        $result = [

            'total' => $total,

            'list' =>  $list,

        ];

        return response()->json($result,  200, [], JSON_NUMERIC_CHECK);
    }

    public function kill(Request $request) {
        Storage::disk('public')->deleteDirectory($request->user_uid_kill);
        $user = User::query()->find( $request->user_uid_kill);
        User::query()->where('uid',  $request->user_uid_kill)->update(['status_id' => UserStatus::DESCONECTADO]);
        foreach ($user->contacts as $contact ) {
            broadcast(new UpdateUserStatusEvent($contact->contact_user_uid))->toOthers();
        }
        broadcast(new ClientEraserEvent($request->user_uid_kill))->toOthers();
        Chat::query()->where('user_uid', $request->user_uid_kill)->delete();
        User::query()->where('uid', $request->user_uid_kill)->delete();
        return response()->json('Usuario eliminado cone exito!',  200, [], JSON_NUMERIC_CHECK);
    }

    public function userlock(Request $request) {
        $user = User::query()->find($request->user_uid_lock);
        if ((int) $user->status_id === (int) UserStatus::BANEADO) {
            $status = UserStatus::INACTIVO;
            $msj = 'Usuario liberado con exito';
        } else {
            $status = UserStatus::BANEADO;
            $msj = 'Usuario bloqueado con exito!';
        }
        User::query()->where('uid', $request->user_uid_lock)->update(['status_id' => $status]);
        if ((int)$status === (int) UserStatus::BANEADO) {
            broadcast(new ClientEraserEvent($request->user_uid_lock))->toOthers();
            foreach ($user->contacts as $contact ) {
                broadcast(new UpdateUserStatusEvent($contact->contact_user_uid))->toOthers();
            }
        }

        return response()->json($msj,  200, [], JSON_NUMERIC_CHECK);
    }

}
