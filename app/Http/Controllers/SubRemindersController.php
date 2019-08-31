<?php

namespace App\Http\Controllers;

use App\Models\SubReminderUser;
use Illuminate\Http\Request;

class SubRemindersController extends Controller
{
    public function  saveSubReminder(Request $request) {
       $subReminder = SubReminderUser::query()->create($request->item);
       $subReminder->users()->createMany($request->sharelist);
       return http_response_code(200);
    }
}
