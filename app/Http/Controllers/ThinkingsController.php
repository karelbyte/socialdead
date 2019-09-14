<?php

namespace App\Http\Controllers;

use App\Models\Thinking;
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
}
