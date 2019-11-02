<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('user-create', 'UsersController@store');
Route::post('user-recovery-token', 'UsersController@recoveryToke');
Route::post('user-password-set', 'UsersController@updatePasswordRecovery');


// COMPEJO DE RUTAS DE EL FRONT ADMIN SOCIAL DEAD
Route::middleware(['auth:admin'])->namespace('Admin')->group(function () {

    Route::prefix('clients')->group(function () {
        Route::post('/list', 'ClientsController@getList');
        Route::post('/kill', 'ClientsController@kill');
        Route::post('/lock', 'ClientsController@userlock');
    });

    Route::prefix('user')->group(function () {
        Route::get('/', 'UsersController@getUser');
    });

    Route::prefix('notifications')->group(function () {
        Route::post('store', 'NotificationsController@store');
    });

    Route::prefix('chats')->group(function () {
        Route::post('messages', 'ChatsController@getMessages');
        Route::post('send-message', 'ChatsController@setMessage');
    });
});

