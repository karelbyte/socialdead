<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentsResource;
use App\Models\Thinking;
use App\Models\ThinkingComment;
use App\Models\VideoComment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ThinkingsController extends Controller
{
    public function save(Request $request) {
        try {
            $uid = $request->user()->uid;
            Thinking::query()->create([
                    'user_uid' => $uid,
                    'moment' => Carbon::now(),
                    'note' =>  $request->note,
                    'title' =>  $request->title,
                    'subtitle' =>  $request->subtitle
            ]);
          return response()->json('Se publico en tu muro!');

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function setComment(Request $request) {
        ThinkingComment::query()->create([
            'from_user' => $request->user()->uid,
            'thinking_id' => $request->id,
            'note' => $request->note,
            'moment' => \Carbon\Carbon::now(),
        ]);
        $comments = ThinkingComment::query()->where('Thinking_id', $request->id)
            ->orderBy('moment', 'desc')->get();

        return CommentsResource::collection($comments);
    }
}
