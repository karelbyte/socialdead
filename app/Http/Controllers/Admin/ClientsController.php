<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\UserOnly;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientsController extends Controller
{
    // OBTINE LA LISTA DE CLIENTES Y LOS ENVIA AL FRONT PAGINADO
    public function getList(Request $request) {

        $skip = $request->input('start') * $request->input('take');

        $filters = $request->input('filters');

        $orders =$request->input('orders');

        $datos = User::query()->select('*');

        if ( $filters['value'] !== '') $datos->where( $filters['field'], 'LIKE', '%'.$filters['value'].'%');

        $datos = $datos->orderby($orders['field'], $orders['type']);

        $total = $datos->count();

        $list =  $datos->skip($skip)->take($request['take'])->get();

        $list = UserOnly::collection($list);

        $result = [

            'total' => $total,

            'list' =>  $list,

        ];

        return response()->json($result,  200, [], JSON_NUMERIC_CHECK);
    }

    /*
    $skip = ((int) $request->input('pagination.page') - 1) * (int) $request->input('pagination.rowsPerPage');


        $datos = User::query()->select('uid', 'avatar', 'full_names', 'email', 'status');

        if ( $request->has('filter')) {

            $datos->where( $request->input('pagination.sortBy'), 'LIKE', '%'. $request->input('filter').'%');
        }

        if ( $request->input('pagination.descending')) {
            $datos->orderby( $request->input('pagination.sortBy'), 'desc');
        } else {
            $datos->orderby( $request->input('pagination.sortBy'), 'asc');
        }

        $total = $datos->count();

        $list =  $datos->skip($skip)->take((int)$request['pagination.rowsPerPage'])->get();

        $result = [

            'total' => $total,

            'list' =>  $list,

        ];
    */
}
