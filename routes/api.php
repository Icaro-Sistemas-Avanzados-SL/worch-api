<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('categories', 'CategoryAPIController');

Route::resource('challenges', 'ChallengeAPIController');

Route::resource('files', 'FileAPIController');

Route::resource('followers', 'FollowerAPIController');

Route::resource('favourites', 'FavouritesAPIController');

Route::resource('ratings', 'RatingAPIController');

Route::resource('users', 'UserAPIController');

Route::resource('notifications', 'NotificationAPIController');

Route::resource('conversations', 'ConversationAPIController');

Route::resource('messages', 'MessageAPIController');

Route::post('login','AuthAPIController@login');

Route::post('check','AuthAPIController@checkLogin');

Route::post('pusher/auth', 'AuthAPIController@pusherAuth');

Route::post('uploadVideo', 'ChallengeAPIController@uploadVideo');
