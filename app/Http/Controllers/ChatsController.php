<?php

namespace App\Http\Controllers;


use App\Events\MessageEvent;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    public function getMessages(Request $request) {
        $data1 = Chat::query()->where('user_uid', $request->user()->uid)
            ->where('for_user_uid', $request->uid)->selectRaw('id, user_uid, msj, status_id, created_at')
            ->get();
        $data2 = Chat::query()->where('user_uid', $request->uid )
            ->where('for_user_uid', $request->user()->uid )->selectRaw('id, user_uid, msj, status_id, created_at')
            ->get();
        $data = $data1->concat($data2);
        $data = Collect($data->sortBy('id')->values()->all());
        $slice = $data->count() - 7;
        return ChatResource::collection($data->slice($slice));
    }

    public function setMessage(Request $request) {
      $msj = Chat::query()->create([
          'user_uid' => $request->uid,
          'for_user_uid' => $request->to,
          'status_id' => 1,
          'msj' =>  $request->msj[0]
       ]);

      broadcast(new MessageEvent($request->to, new ChatResource($msj) ))->toOthers();
      return http_response_code(200);
    }
}
