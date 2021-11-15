<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Books\BooksController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocatorController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//   return $request->user();
// });
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('updateUser', [UserController::class, 'updateUser']);
Route::post('updatePassword', [UserController::class, 'updatePassword']);
Route::post('validateUser', [UserController::class, 'validateUser']);
Route::post('sendVerifyEmail', 'UserController@sendVerifyEmail');
Route::post('sendVerifyPhone', 'UserController@sendVerifyPhone');
Route::post('uploadUserAvatar', 'UserController@uploadUserAvatar');
Route::post('forgotPassword', 'ResetPasswordController@sendPasswordResetEmail');
Route::post('resetPassword', 'ResetPasswordController@resetPassword');

Route::post('/getAreaData', [LocatorController::class, 'getAreaData']);

Route::middleware('auth:api')->group(function () {
  Route::post('/updateUser', 'UserController@updateUser');
  Route::post('/removeUser', 'UserController@removeUser');
  Route::post('/confirmUser', 'UserController@confirmUser');
  Route::get('/my-profile', 'UserController@myProfile');
  Route::post('/update-profile', 'UserController@updateProfile');
  Route::post('/getClients', 'UserController@getClients');
  Route::post('/getUsers', 'UserController@getUsers');
  Route::get('/getClientDetails', 'UserController@getClientDetails');
  Route::get('/getUserDetails', 'UserController@getUserDetails');
});

Route::group(['middleware' => 'auth.basic'], function () {
  Route::apiResource('books', BooksController::class);
});
