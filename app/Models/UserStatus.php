<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property boolean $id
 * @property string $descriptor
 */
class UserStatus extends Model
{

    protected $table = 'users_status';

    protected $fillable = ['descriptor'];

}
