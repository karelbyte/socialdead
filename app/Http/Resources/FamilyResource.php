<?php

namespace App\Http\Resources;

use App\Traits\Zodiac;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class FamilyResource extends JsonResource
{
    use Zodiac;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $avatar = $this->contact->avatar === null ? Image::make($this->symbol($this->contact->birthdate)['url'])->encode('data-url')
            : Image::make(storage_path('app/public/') . $this->contact_user_uid . '/profile/avatar/' . $this->contact->avatar)->resize(150, 150)->encode('data-url', 50);


        switch ($this->kin->id) {
            case 1: // PAPA
                $col = 1;
                break;
            case 2; // MAMA
                $col = 1;
                break;
            case 3: // ABUELO M
                $col = 0;
                break;
            case 4: // ABUELA M
                $col = 0;
                break;
            case 5: // ABUELO P
                $col = 0;
                break;
            case 6: // ABUELA P
                $col = 0;
                break;
            case 13; // HIJO
                $col = 4;
                break;
            case 14; // HIJA
                $col = 4;
                break;
            case 7; // HERMANO
                $col = 3;
                break;
            case 8: // HERMANA
                $col = 3;
                break;
            default:
                $col = 10;
        }
        return [
            'id' => $this->contact_user_uid,
            'name' => (string) $this->contact->full_names,
            'title'  => $this->kin->descriptor,
            'column' => $col,
            'image' => $avatar->encoded
        ];
    }
}
