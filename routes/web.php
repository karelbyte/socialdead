<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

// CONFIRMACION DE CUENTA DEL USUARIO SOCIALDEAD
Route::get('/confirmacion-de-cuenta/{token}', 'UsersController@confirmAcount');

// COMPLETAR RECORDATORIO
Route::get('/recuerdos/{token}', 'RemindersController@IndexSubReminder');
Route::post('/recuerdos/actualizar', 'RemindersController@UpdateSubReminder');
Route::get('/recuerdos/remover/{token}', 'RemindersController@cancelReminderEmail');


