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

Route::get('/user/friends_location', function (Request $request) {
    return FriendsController::getFriendsLocations($request);
});

Route::get('/user/friends_location/{id}', function (Request $request, $id) {
    return FriendsController::getFriendWithLocation($id);
});

Route::get('/user/friends', function (Request $request) {
    return FriendsController::getUsersFriends($request);
});

Route::post('/user/friends', function (Request $request) {
    return FriendsController::addFriend($request);
});

Route::post('/user/friends/remove', function (Request $request) {
    return FriendsController::removeFriend($request);
});

Route::get('/user/friends_within_radius', function (Request $request) {
    return FriendsController::getAllFriendsWithinRadius($request, 300);
});

Route::get('/user/friends_safe_zones', function (Request $request) {
    return FriendsController::getFriendsSafeZones($request);
});

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

Route::get('/user/active', function(Request $request){
    return UsersController::toggleActive($request);
});

Route::get('/user/{id}', function (Request $request, $id) {
    return UsersController::getOtherUser($request, $id);
});

Route::post('/user/points/add', function (Request $request) {
    return UsersController::addPoints($request);
});

Route::post('/user/points/subtract', function (Request $request) {
    return UsersController::subtractPoints($request);
});

Route::post('/user/active', function (Request $request) {
    return UsersController::setActive($request);
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

Route::post('/safe_zone/in_safe_zone', function (Request $request){
    return SafeZoneController::setInSafeZone($request);
});


