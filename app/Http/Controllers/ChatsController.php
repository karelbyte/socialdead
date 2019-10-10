<?php

namespace App\Http\Controllers;


use App\Events\MessageEvent;
use App\Http\Resources\ChatListResource;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Traits\UserFileStore;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ChatsController extends Controller
{
    use UserFileStore;

    public function getMessages(Request $request) {
        $data1 = Chat::query()->where('user_uid', $request->user()->uid)
            ->where('for_user_uid', $request->uid)->selectRaw('id, user_uid, msj, status_id, type, created_at')
            ->get();
        $data2 = Chat::query()->where('user_uid', $request->uid )
            ->where('for_user_uid', $request->user()->uid )->selectRaw('id, user_uid, msj, status_id, type, created_at')
            ->get();
        $data = $data1->concat($data2);
        $data = Collect($data->sortBy('id')->values()->all());
        return ChatListResource::collection($data);
    }

    public function setMessage(Request $request) {
          $msj = Chat::query()->create([
              'user_uid' => $request->uid,
              'for_user_uid' => $request->to,
              'status_id' => 1,
              'type' => $request->type,
              'msj' =>  $request->msj[$request->type]
          ]);

      broadcast(new MessageEvent($request->to, new ChatResource($msj) ))->toOthers();
      return http_response_code(200);
    }

    public function setMessage_file(Request $request) {

        $uid = $request->user()->uid;
        $file = $request->file;
        if ( $this->store($uid, $request->file->getSize())) {
            $ext = strtoupper($file->getClientOriginalExtension());
            $name = Carbon::now()->timestamp . '.'.$ext;
            $patch = storage_path('app/public/') . $uid .'/files';
            File::exists( $patch) or File::makeDirectory($patch , 0777, true, true);
            $request->file->storeAs('public/'.$uid .'/files/', $name);

            $msj = Chat::query()->create([
                'user_uid' => $request->uid,
                'for_user_uid' => $request->to,
                'status_id' => 1,
                'type' => 'file',
                'msj' => $name
            ]);
            $toSend = new ChatResource($msj);
            broadcast(new MessageEvent($request->to, $toSend))->toOthers();
            return response()->json($toSend);
        } else {
            return response()->json('El tamaño del archivo se sobrepasa el limite de megas que tiene contratado, le sugerimos adquirir un extensión de almacenamiento!', 500);

        }

    }
}
