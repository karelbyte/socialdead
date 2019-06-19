<?php

namespace App\Http\Controllers;

use App\Events\UpdateUserStatusEvent;
use App\Http\Resources\ContactFullResource;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\Kin;
use App\Models\Notification;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    public function getContactsOnline(Request $request) {

        $data = Contact::query()->join('users', 'users.uid','contacts.contact_user_uid')
            ->where('contacts.user_uid', $request->user()->uid)
            ->wherein('users.status_id', [1, 2])
            ->get();

        return response()->json(ContactResource::collection($data));
    }

    public function getContactsAll(Request $request) {

        $data = Contact::query()->with('kin')->join('users', 'users.uid','contacts.contact_user_uid')
            ->where('contacts.user_uid', $request->user()->uid)
            ->select('users.full_names', 'users.occupation', 'users.avatar',  'users.who_you_are', 'users.birthdate',
                'users.status_id as status_user', 'contacts.*')
            ->get();

        return  response()->json(ContactFullResource::collection($data));
    }

    public function setContactsUpdate(Request $request) {

        Contact::query()
            ->where('user_uid', $request->user()->uid)
            ->where('contact_user_uid', $request->uid)
            ->update([
              'type_id' => $request->type,
              'kin_id' => $request->kin
            ]);
        return http_response_code(200);
    }

    public function setConfirmContact(Request $request) {
        $notification = Notification::query()->find($request->notification);
        if ($request->confirm === 1 && $request->user()->isContact($notification->from_user)) {
            Contact::query()->create([
                'user_uid' =>  $notification->from_user,
                'contact_user_uid' => $request->user()->uid,
                'type_id' => $request->type,
                'status_id' => 1,
            ]);
            $request->user()->contacts()->create([
                'contact_user_uid' => $notification->from_user,
                'type_id' => $request->type,
                'status_id' => 1
            ]);
           broadcast(new UpdateUserStatusEvent($notification->from_user))->toOthers();
           broadcast(new UpdateUserStatusEvent($request->user()->uid))->toOthers();
        }
        $notification->update(['status_id' =>  2]);  // LEIDO
        return http_response_code(200); //  $notification;
    }

    function allKins () {
        return Kin::all();
    }
}
