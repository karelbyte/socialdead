<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\Admin\ClientResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    // ONTENIENDO EL PERFIL DEL USUARIO ACTIVO
    public function getUser (Request $request) {
        return new AdminResource($request->user());
    }
}
