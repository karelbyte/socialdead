<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property boolean $id
 * @property string $descriptor
 */
class UserSex extends Model
{

    protected $table = 'users_sex';


    protected $fillable = ['descriptor'];

}
