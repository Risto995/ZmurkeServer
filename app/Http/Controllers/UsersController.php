<?php
/**
 * Created by PhpStorm.
 * User: Riki
 * Date: 6/24/2017
 * Time: 9:41 PM
 */

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Game;
use App\Location;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;


class UsersController extends Controller
{
    public static function getAllUsers(Request $request){
        return User::all();
    }

    public static function getUser(Request $request, $id){
        return User::find($id);
    }

    public static function getUsersFriends(Request $request, $id){
        return User::find($id)->friends()->get();
    }

    public static function getCurrentLocation(Request $request, $id){
        return User::find($id)->location()->get();
    }

    public static function getLocations(Request $request, $id){
        return User::find($id)->locations()->get();
    }

    public static function getCurrentGame(Request $request, $id){
        return User::find($id)->game()->get();
    }

    public static function getPlayersInCurrentGame(Request $request, $id){
        $game = User::find($id)->game()->first();
        return Game::find($game['id'])->players()->get();
    }

    public static function postCurrentLocation(Request $request, $id){

        if(!$request->has('latitude') || !$request->has('longitude'))
            throw new MissingMandatoryParametersException('You need to provide latitude and longitude for a new location');


        Location::where('active', true)
            ->where('user_id', $id)
            ->update(['active' => false]);

        $location = new Location();

        $location->latitude = $request->get('latitude');
        $location->longitude = $request->get('longitude');
        $location->user_id = $id;
        $location->active = true;

        $location->save();

        $user = User::find($id)->first();
        $user->current_location = $location->id;
        $user->save();
    }
}