<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Game;
use App\Location;
use App\SafeZone;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

use App\Http\Requests;

class LocationController extends Controller
{

    public static function getCurrentLocation(Request $request, $id){

        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('id', $id)->first();

        return $user->location;
    }

    public static function getLocations(Request $request, $id){

        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        return User::find($id)->locations()->get();
    }

    public static function postCurrentLocation(Request $request){

        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        if(!$request->has('latitude') || !$request->has('longitude'))
            throw new MissingMandatoryParametersException('You need to provide latitude and longitude for a new location');

        $user = User::where('api_token', $request->header('api'))->first();


        Location::where('active', true)
            ->where('user_id', $user->id)
            ->update(['active' => false]);

        $location = new Location();

        $location->latitude = $request->get('latitude');
        $location->longitude = $request->get('longitude');
        $location->user_id = $user->id;
        $location->active = true;

        $location->save();
        $user->current_location = $location->id;


        $safeZone = SafeZone::where('id',$user->safe_zone)->first();
        if($safeZone != null) {
            $startLocation = Location::where('id', $safeZone->start_location)->first();
            $endLocation = Location::where('id', $safeZone->end_location)->first();
            if ($location->latitude >= $startLocation->latitude &&
                $location->latitude <= $endLocation->latitude &&
                $location->longitude >= $startLocation->longitude &&
                $location->longitude <= $endLocation->longitude
            ) {
                if (!$user->in_safe_zone)
                    $user->timer = 10;
                else {
                    $user->timer--;
                    if ($user->timer <= 0)
                        $user->points -= 100;
                }

                $user->in_safe_zone = true;
            }
            else {
                $user->in_safe_zone = false;
                $user->timer = 0;
            }
        }

        $user->save();
        return $location;
    }
}
