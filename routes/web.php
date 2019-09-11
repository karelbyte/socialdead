<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// CONFIRMACION DE CUENTA DEL USUARIO SOCIALDEAD
Route::get('/confirmacion-de-cuenta/{token}', 'UsersController@confirmAcount');

// COMPLETAR RECORDATORIO
Route::get('/recuerdos/{token}', 'RemindersController@IndexSubReminder');
Route::post('/recuerdos/actualizar', 'RemindersController@UpdateSubReminder');
Route::get('/recuerdos/remover/{token}', 'RemindersController@cancelReminderEmail');

Route::get('/prueba', function () {

});


