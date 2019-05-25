<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property boolean $id
 * @property string $descriptor
 */
class UserCivilStatus extends Model
{

    protected $table = 'users_civil_status';

    protected $fillable = ['descriptor'];

}
