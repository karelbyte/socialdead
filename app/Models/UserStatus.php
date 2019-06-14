<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property boolean $id
 * @property string $descriptor
 */
class UserStatus extends Model
{
    const ACTIVO = 1;
    const INACTIVO = 2;
    const DESCONECTADO = 3;

    protected $table = 'users_status';

    protected $fillable = ['descriptor'];

}
