<?php

namespace App\Http\Controllers;

use App\Http\Resources\FamilyResource;
use App\Models\Contact;
use App\Traits\Zodiac;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class TreeController extends Controller
{
    use Zodiac;

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

        // MIS HIJOS
        $sons = Contact::query()->where('user_uid', $user_uid)->whereIn('kin_id', [13, 14])->get();
            if (count($sons) > 0) {
                foreach ( $sons as $son) {
                    $data[] = [$user_uid, $son->contact_user_uid];
                }
            }

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

        // PAPA CON HERMANOS
        if ($father !== null)  {
            $data[] = [$father->contact_user_uid, $user_uid];
            if (count($brothers) > 0) {
                foreach ( $brothers as $brother) {
                    $data[] = [$father->contact_user_uid, $brother->contact_user_uid];
                }
            }
        }

        // MAMA CON HERMANOS
        if ($mother !== null) {
            $data[] = [$mother->contact_user_uid, $user_uid];
            if (count($brothers) > 0) {
                foreach ( $brothers as $brother) {
                    $data[] = [$mother->contact_user_uid, $brother->contact_user_uid];
                }
            }
        }

        // YO CON HERMANOS SI NO ESTAN MIS PAPAS
        if ($father === null && $mother === null)  {
            if (count($brothers) > 0) {
                foreach ( $brothers as $brother) {
                    $data[] = [ $user_uid , $brother->contact_user_uid];
                }
            }
        }


        // BUSCANDO LOS NODOS FAMILIARES Y GENERANDO MI NODO

        $family = Contact::query()->with('contact', 'kin')->where('user_uid', $user_uid)->where('type_id', 2)->get();

        $avatar = $request->user()->avatar === null ? Image::make($this->symbol($request->user()->birthdate)['url'])->encode('data-url')
            : Image::make(storage_path('app/public/') . $user_uid . '/profile/avatar/' . $request->user()->avatar)->resize(150, 150)->encode('data-url', 50);

        $me = Collect([
            'id' =>  $user_uid,
            'name' => $request->user()->full_names,
            'title'  => '',
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
