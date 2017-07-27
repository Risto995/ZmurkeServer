<?php

use Illuminate\Http\Request;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\SafeZoneController;
use App\Http\Controllers\FriendsController;
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

Route::post('/login', function (Request $request) {
    return UsersController::login($request);
});

Route::post('/register', function (Request $request) {
    return UsersController::register($request);
});

Route::post('/update', function (Request $request) {
    return UsersController::update($request);
});

Route::get('/users', function(Request $request){
    return UsersController::getAllUsers($request);
});

Route::get('/user', function (Request $request) {
    return UsersController::getUser($request);
});

Route::get('/user/friends', function (Request $request) {
    return FriendsController::getUsersFriends($request);
});

Route::post('/user/friends', function (Request $request) {
    return FriendsController::addFriend($request);
});

Route::get('/user/friends_within_radius', function (Request $request) {
    return FriendsController::getAllFriendsWithinRadius($request, 300);
});

Route::get('/user/{id}/location', function (Request $request, $id) {
    return LocationController::getCurrentLocation($request, $id);
});

Route::get('/user/{id}/locations', function (Request $request, $id) {
    return LocationController::getLocations($request, $id);
});

Route::post('/location', function (Request $request){
    return LocationController::postCurrentLocation($request);
});

Route::get('/game', function (Request $request) {
    return GameController::getCurrentGame($request);
});

Route::get('/players', function (Request $request) {
    return GameController::getPlayersInCurrentGame($request);
});

Route::post('/game', function (Request $request){
    return GameController::createNewGame($request);
});

Route::get('/game/end', function (Request $request) {
    return GameController::endGame($request);
});

Route::get('/winner', function (Request $request) {
    return GameController::getWinner($request);
});

Route::get('/safe_zone', function (Request $request){
    return SafeZoneController::getSafeZone($request);
});

Route::post('/safe_zone', function (Request $request){
    return SafeZoneController::createSafeZone($request);
});




