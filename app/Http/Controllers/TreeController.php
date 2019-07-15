<?php

namespace App\Http\Controllers;

use App\Http\Resources\FamilyResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use mysql_xdevapi\Collection;

class TreeController extends Controller
{

    public function getTree(Request $request) {

        $data = [];

        $user_uid = $request->user()->uid;

        // ABUELO PATERNO
        $granFather = Contact::query()->where('user_uid', $user_uid)->where('kin_id', 5)->first();
        // ABUELA PATERNO
        $granMother= Contact::query()->where('user_uid', $user_uid)->where('kin_id', 6)->first();

        // ABUELO MATERNO
        $granFatherM = Contact::query()->where('user_uid', $user_uid)->where('kin_id', 3)->first();
        // ABUELA MATERNO
        $granMotherM = Contact::query()->where('user_uid', $user_uid)->where('kin_id', 4)->first();

        // PAPA
        $father = Contact::query()->where('user_uid', $user_uid)->where('kin_id', 1)->first();

        // MAMA
        $mother = Contact::query()->where('user_uid', $user_uid)->where('kin_id', 2)->first();


        // ABUELO MATERNO
        if ($father !== null && $granFatherM !== null)  {
            $data[] = [$granFatherM->contact_user_uid, $father->contact_user_uid];
        }
        // ABUELA MATERNO
        if ($father !== null && $granMotherM !== null)  {
            $data[] = [$granMotherM->contact_user_uid, $father->contact_user_uid];
        }


        // ABUELO PATERNO
        if ($father !== null && $granFather !== null)  {
            $data[] = [$granFather->contact_user_uid, $mother->contact_user_uid];
        }
        // ABUELO PATERNO
        if ($father !== null && $granMother !== null)  {
            $data[] = [$granMother->contact_user_uid, $mother->contact_user_uid];
        }

        $brothers = Contact::query()->where('user_uid', $user_uid)->whereIn('kin_id', [7, 8])->get();

        if ($father !== null)  {
            $data[] = [$father->contact_user_uid, $user_uid];
            if (count($brothers) > 0) {
                foreach ( $brothers as $brother) {
                    $data[] = [$father->contact_user_uid, $brother->contact_user_uid];
                }
            }
        }


        if ($mother !== null) {
            $data[] = [$mother->contact_user_uid, $user_uid];
            if (count($brothers) > 0) {
                foreach ( $brothers as $brother) {
                    $data[] = [$mother->contact_user_uid, $brother->contact_user_uid];
                }
            }
        }

        $family = Contact::query()->with('contact', 'kin')->where('user_uid', $user_uid)->where('type_id', 2)->get();

        // CONTRUYENDO A MI

        $avatar = $request->user()->avatar === null ? Image::make($this->symbol($request->user()->birthdate)['url'])->encode('data-url')
            : Image::make(storage_path('app/public/') . $user_uid . '/profile/avatar/' . $request->user()->avatar)->resize(150, 150)->encode('data-url', 50);

        $me = Collect([
            'id' =>  $user_uid,
            'name' => $request->user()->full_names,
            'title'  => 'Hijo',
            'column' => 3,
            'image' => $avatar->encoded
        ]);

        $nodes = FamilyResource::collection($family)->collection;

        $result = [
            'data' => $data,
            'nodes' => $nodes->add($me)
        ];

        return  $result;
    }
}
