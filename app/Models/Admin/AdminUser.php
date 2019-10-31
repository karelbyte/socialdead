<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;

class AdminUser extends Authenticatable
{
    use HasMultiAuthApiTokens,  Notifiable;

    protected $primaryKey = 'uid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'admin_users';

    protected $fillable = [
        'names', 'email', 'phone', 'status_id']
    ;

}
