<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Friends;
use App\Location;
use Psy\Exception\ErrorException;
use Symfony\Component\Finder\Exception\AccessDeniedException;

use App\Http\Requests;

class FriendsController extends Controller
{
    public static function getUsersFriends(Request $request){

        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();

        $friendsRelationship = Friends::where('first_user', $user->id)->orWhere('second_user', $user->id)->get();

        $friends = [];
        foreach ($friendsRelationship as $frR){
            $id = null;
            if($frR->first_user == $user->id)
                $id = $frR->second_user;
            else
                $id = $frR->first_user;
            $f = User::where('id', $id)->first();
            array_push($friends, $f);
        }
        if($friends == null)
            throw new ErrorException('This user has no friends :(');

        return $friends;
    }

    public static function addFriend(Request $request){
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();

        $friends = new Friends();
        $friends->first_user = $user->id;
        $friends->second_user = $request->get('friend_id');
        $friends->save();

        /*$reverseFriends = new Friends();
        $reverseFriends->first_user = $request->get('friend_id');
        $reverseFriends->second_user = $user->id;
        $reverseFriends->save();*/

        return $friends;
    }

    public static function getFriendsLocations(Request $request){
        $friends = self::getUsersFriends($request);
        $list = [];
        foreach ($friends as $friend){
            $info = self::getFriendWithLocation($friend->id);
            $value = json_decode($info->content());
            array_push($list, $value);
        }

        return $list;
    }

    public static function getFriendWithLocation($id){
        $user = User::where('id', $id)->first();
        $location = Location::where('user_id', $id)->where('active', true)->first();
        return response()->json([
            'name' => $user->name,
            'avatar' => $user->avatar,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude
        ]);
    }

    public static function getAllFriendsWithinRadius(Request $request, $radius){
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();
        $usersLocation = LocationController::getCurrentLocation($request, $user->id);
        $friendsRelationship = Friends::where('first_user', $user->id)->orWhere('second_user', $user->id)->get();
        $friends = [];
        foreach ($friendsRelationship as $frR){
            $id = null;
            if($frR->first_user == $user->id)
                $id = $frR->second_user;
            else
                $id = $frR->first_user;
            $f = User::where('id', $id)->first();
            array_push($friends, $f);
        }
        if($friends == null)
            throw new ErrorException('This user has no friends :(');

        $friendsWithinRadius = [];


        foreach ($friends as $friend){
            $location = Location::where('user_id', $friend->id)->where('active', true)->first();
            //$location = Location::where('user_id', $friend->id)->where('active',true)->first();
            //http://janmatuschek.de/LatitudeLongitudeBoundingCoordinates
            //acos(sin(1.3963) * sin(Lat) + cos(1.3963) * cos(Lat) * cos(Lon - (-0.6981))) * 6371 <= 1000;
            if($location != null && acos(sin($usersLocation->latitude) * sin($location->latitude) + cos($usersLocation->latitude) * cos($location->latitude) * cos($location->longitude - $usersLocation->longitude)) * 6371 <= $radius && $friend->game == $user->game)
                array_push($friendsWithinRadius, $friend);
            if($location != null && acos(sin($usersLocation->latitude) * sin($location->latitude) + cos($usersLocation->latitude) * cos($location->latitude) * cos($location->longitude - $usersLocation->longitude)) * 6371 == 0 && $user->hunter && $friend->game == $user->game) {
                $fr = User::where('id', $friend->id)->first();
                if(!$fr->caught) {
                    $user->points += 10;
                    $fr->caught = true;
                    $fr->save();
                    $user->save();
                }
            }

        }

        return $friendsWithinRadius;
    }
}
