<?php

namespace App\Http\Controllers;

use App\Http\Resources\HistoryResource;
use App\Models\History;
use Illuminate\Http\Request;

class HistoriesController extends Controller
{
    public function getHistories(Request $request) {

        $data = History::query()->where('user_id', $request->user()->uid)
            ->orderBy('moment', 'desc')
            ->get();
        return HistoryResource::collection($data);
    }
}
