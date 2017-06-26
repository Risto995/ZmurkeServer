<?php

use Illuminate\Http\Request;

use App\Http\Controllers\UsersController;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/users', function(Request $request){
    return UsersController::getAllUsers($request);
});

Route::get('/users/{id}', function (Request $request, $id) {
    return UsersController::getUser($request, $id);
});

Route::get('/users/{id}/friends', function (Request $request, $id) {
    return UsersController::getUsersFriends($request, $id);
});

Route::get('/users/{id}/location', function (Request $request, $id) {
    return UsersController::getCurrentLocation($request, $id);
});

Route::get('/users/{id}/locations', function (Request $request, $id) {
    return UsersController::getLocations($request, $id);
});

Route::get('/users/{id}/game', function (Request $request, $id) {
    return UsersController::getCurrentGame($request, $id);
});

Route::get('/users/{id}/players', function (Request $request, $id) {
    return UsersController::getPlayersInCurrentGame($request, $id);
});

Route::post('/users/{id}/location', function (Request $request, $id){
    return UsersController::postCurrentLocation($request, $id);
});
