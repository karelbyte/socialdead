<?php


namespace App\Traits;


use App\Models\UserStore;
use Illuminate\Support\Facades\Storage;

trait UserFileStore
{

 public function store ($uid, $size) {

     $inStore = UserStore::query()->where('user_uid', $uid)->first();

     $fileSize =  $size  / 1048576.2;

     if ( $inStore == null) {
         $inStore =   UserStore::query()->create([
             'user_uid' =>  $uid,
             'gigas' => 2,
             'inuse' => 0
         ]);
     }

     if (($fileSize + $inStore->inuse) > ($inStore->gigas * 1000)) {
         return ['pass' => false];
     } else {
         $fix =  round(($fileSize), 4);
         $inStore->inuse +=  $fix;
         $inStore->save();
         return ['pass' => true, 'size' =>  $fileSize];
     }
 }

 public function restStore($uid, $size) {
     $inStore = UserStore::query()->where('user_uid', $uid)->first();
     $inStore->inuse -= $size;
     $inStore->save();
 }

}