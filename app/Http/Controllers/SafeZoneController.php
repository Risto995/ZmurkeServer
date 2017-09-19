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

        $safeZone = SafeZone::where('user_id', $user->id)->first();

        return $safeZone;
    }

    public static function getAnySafeZone($id)
    {
        $safeZone = SafeZone::where('user_id', $id)->first();

        return $safeZone;
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

        $safeZone->latitude = $request->get('latitude');
        $safeZone->longitude = $request->get('longitude');
        $safeZone->user_id = $user->id;
        $safeZone->save();

        $user->safe_zone = $safeZone->id;
        $user->save();

        return $safeZone;
    }

    public static function setInSafeZone(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();

        $user->in_safe_zone = $request->get('in_safe_zone') == "true" ? 1 : 0;
        $user->save();

        return $user;
    }
}
