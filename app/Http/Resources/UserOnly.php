<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserOnly extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       return [
         'uid' => $this->uid,
         'full_names' =>  $this->full_names,
         'email' => $this->email,
         'phone' => $this->phone,
         'address' => $this->address,
         'nif' => $this->nif,
         'avatar' => $this->avatar,
         'birthdate' => Carbon::parse($this->birthdate)->format('d-m-Y'),
         'sex' =>  $this->sex,
         'occupation' => $this->occupation,
         'civil' => $this->civil,
         'birthplace' => $this->birthplace,
         'country' => $this->country,
         'who_you_are' => $this->who_you_are,
         'website' => $this->website,
         'facebook' => $this->facebook,
         'twitter' => $this->twitter,
         'religion' => $this->religion,
         'politics' => $this->politics,
         'status'  => $this->status
       ];
    }
}
