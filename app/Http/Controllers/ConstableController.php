<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Notification;
use Illuminate\Http\Request;

class ConstableController extends Controller
{
    public function setConfirm(Request $request) {
        $notification = Notification::query()->find($request->notification);
        if ($request->confirm === 1 ) {
            Contact::query()->where('user_uid',  $notification->from_user)
                ->where('contact_user_uid', $notification->to_user)
                ->update([
                 'constable' => 2
                ]);
        }
        $notification->update(['status_id' =>  2]);  // LEIDO
        return http_response_code(200);
    }
}
