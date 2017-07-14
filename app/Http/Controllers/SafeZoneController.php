<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Game;
use App\Location;
use App\SafeZone;

use App\Http\Requests;
use Psy\Exception\ErrorException;

class SafeZoneController extends Controller
{
    public static function getSafeZone(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();

        return $user->safeZone()->with('startLocation')->with('endLocation')->get();
    }

    public static function createSafeZone(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        //user can't change his safe zone once he created it
        $user = User::where('api_token', $request->header('api'))->first();
        if($user->safe_zone != null)
            throw new ErrorException('This user already has a safe zone');

        $safeZone = new SafeZone();
        $startLocation = new Location();
        $endLocation = new Location();

        $startLocation->latitude = $request->get('start_latitude');
        $startLocation->longitude = $request->get('start_longitude');
        $startLocation->user_id = $user->id;
        $startLocation->active = false;
        $startLocation->save();

        $endLocation->latitude = $request->get('end_latitude');
        $endLocation->longitude = $request->get('end_longitude');
        $endLocation->user_id = $user->id;
        $endLocation->active = false;
        $endLocation->save();

        $safeZone->start_location = $startLocation->id;
        $safeZone->end_location = $endLocation->id;
        $safeZone->user_id = $user->id;
        $safeZone->save();

        $user->safe_zone = $safeZone->id;
        $user->save();

        return $safeZone;
    }
}
