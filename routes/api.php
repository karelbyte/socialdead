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

Route::middleware('auth:api')->get('/users', function (Request $request) {
    return new \App\Http\Resources\UserProfileGeneral($request->user());
});

Route::post('user-create', 'UsersController@store');
Route::post('user-recovery-token', 'UsersController@recoveryToke');
Route::post('user-password-set', 'UsersController@updatePasswordRecovery');

Route::middleware(['auth:api'])->group(function () {

    Route::post('users/search', 'UsersController@search');

    Route::prefix('index')->group(function () {
        Route::post('/profile-data', 'IndexController@getProfileData');
        Route::post('/wall', 'IndexController@getWall');
    });

    Route::prefix('histories')->group(function () {
        Route::get('/', 'HistoriesController@getHistories');
    });

    Route::prefix('user')->group(function () {
        Route::get('/', 'UsersController@getProfile');
        Route::post('status', 'UsersController@updateStatus');
        Route::post('update-password', 'UsersController@updatePassword');
        Route::post('update-email', 'UsersController@updateEmail');
        Route::post('exit', 'UsersController@exit');
        Route::get('public-data/{uid}', 'UsersController@getDataPublic');
    });

    Route::prefix('notifications')->group(function () {
        Route::post('store', 'NotificationsController@store');
        Route::post('list', 'NotificationsController@getNotificatios');
        Route::post('all', 'NotificationsController@getNotificatiosAll');
        Route::post('off-all', 'NotificationsController@offAllNotification');
        Route::post('eraser', 'NotificationsController@eraser');
        Route::post('update-settings', 'NotificationsController@updateNotificationsSettings');
        Route::post('settings', 'NotificationsController@getSettings');
    });

    Route::prefix('constables')->group(function () {
        Route::post('confirm', 'ConstableController@setConfirm');
        Route::post('list', 'ConstableController@getConstables');
    });

    Route::prefix('contacts')->group(function () {
        Route::post('list', 'ContactsController@getContactsOnline');
        Route::post('list-all', 'ContactsController@getContactsAll');
        Route::post('confirm', 'ContactsController@setConfirmContact');
        Route::get('list-kins', 'ContactsController@allKins');
        Route::post('update', 'ContactsController@setContactsUpdate');
        Route::post('kill', 'ContactsController@contactDelete');

    });

    Route::prefix('profile')->group(function () {

        Route::get('/', 'ProfilesController@getProfile');
        Route::post('/update', 'ProfilesController@updateProfile');
        Route::post('/avatar-update', 'ProfilesController@updateProfileAvatar');

        Route::get('/jobs', 'ProfilesController@getProfileJobs');
        Route::post('/job-add', 'ProfilesController@addProfileJob');
        Route::post('/job-update', 'ProfilesController@updateProfileJob');
        Route::post('/job-delete', 'ProfilesController@deleteProfileJob');

        Route::get('/hobbies', 'ProfilesController@getProfileHobbies');
        Route::post('/hobbies-add', 'ProfilesController@addProfileHobbies');

    });

    Route::prefix('photos')->group(function () {
        Route::get('lists', 'PhotosController@getPhotosLists');
        Route::post('save', 'PhotosController@savePhoto');
        Route::delete('delete/{id}', 'PhotosController@destroyPhoto');
        Route::get('photo/{id}', 'PhotosController@getPhoto');
        Route::post('update', 'PhotosController@updatePhoto');
        Route::post('to-history', 'PhotosController@toHistory');
        Route::post('share', 'PhotosController@sharePhoto');
        Route::post('comment-save', 'PhotosController@setComment');
    });

    Route::prefix('videos')->group(function () {
        Route::get('lists', 'VideosController@getVideoLists');
        Route::post('save', 'VideosController@saveVideos');
        Route::delete('delete/{id}', 'VideosController@destroyVideo');
        Route::get('video/{id}', 'VideosController@getVideo');
        Route::post('update', 'VideosController@updateVideo');
        Route::post('to-history', 'VideosController@toHistory');
        Route::post('share', 'VideosController@shareVideo');
        Route::post('comment-save', 'VideosController@setComment');
    });

    Route::prefix('audios')->group(function () {
        Route::get('lists', 'AudiosController@getVideoLists');
        Route::post('save', 'AudiosController@saveAudio');
        Route::delete('delete/{id}', 'AudiosController@destroyAudio');
        Route::get('audio/{id}', 'AudiosController@getAudio');
        Route::post('update', 'AudiosController@updateAudio');
        Route::post('to-history', 'AudiosController@toHistory');
        Route::post('share', 'AudiosController@shareAudio');
        Route::post('comment-save', 'AudiosController@setComment');
    });

    Route::prefix('medias')->group(function () {
        Route::get('lists', 'MediasController@getLists');
    });

    Route::prefix('chats')->group(function () {
        Route::post('messages', 'ChatsController@getMessages');
        Route::post('send-message', 'ChatsController@setMessage');
        Route::post('send-message-file', 'ChatsController@setMessage_file');
    });

    Route::prefix('reminders')->group(function () {
        Route::post('lists', 'RemindersController@getList');
        Route::post('kill', 'RemindersController@ReminderDelete');
        Route::post('save', 'RemindersController@saveReminder');
        Route::post('update', 'RemindersController@updateReminder');
        Route::post('share', 'RemindersController@shareReminder');
        Route::post('accept', 'RemindersController@AcceptReminder');
        Route::post('off-noty', 'RemindersController@OffNotyReminder');
        Route::post('sub-save', 'RemindersController@saveSubReminder');
        Route::post('get-sub', 'RemindersController@getSubReminder');
        Route::post('up-sub', 'RemindersController@UpdateSubReminderFromSD');
        Route::get('get-types', 'RemindersController@getTypes');
        Route::post('comment-save', 'RemindersController@setComment');
    });

    Route::prefix('capsules')->group(function () {
        Route::post('lists', 'CapsulesController@getList');
        Route::post('save', 'CapsulesController@save');
        Route::post('kill', 'CapsulesController@delete');
        Route::post('update', 'CapsulesController@update');
        Route::post('capsule-close', 'CapsulesController@capsuleClose');
    });

    Route::prefix('files')->group(function () {
        Route::post('lists', 'FilesController@getAllFiles');
        Route::post('get-file', 'FilesController@getFile');
        Route::post('kill', 'FilesController@delete');
    });


    Route::prefix('thinkings')->group(function () {
        Route::post('lists', 'ThinkingsController@getList');
        Route::post('save', 'ThinkingsController@save');
        Route::post('comment-save', 'ThinkingsController@setComment');
    });


    Route::prefix('tree')->group(function () {
        Route::post('family', 'TreeController@getTree');
    });

});

