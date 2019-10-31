<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageEvent;
use App\Events\SystemMessageEvent;
use App\Http\Resources\Admin\SystemChatResource;
use App\Http\Resources\ChatListResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatsController extends Controller
{

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

      broadcast(new SystemMessageEvent($request->to, new SystemChatResource($msj) ))->toOthers();
      return http_response_code(200);
    }

}
