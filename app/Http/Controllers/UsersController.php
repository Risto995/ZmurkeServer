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
use App\SafeZone;
use Psy\Exception\ErrorException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Auth;
use Illuminate\Support\Facades\Input;
use Faker\Factory as Faker;

class UsersController extends Controller
{

    public static function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            // Authentication passed...
            $user = Auth::user();
            return $user['api_token'];
        } else {
            throw new AccessDeniedException('Email or password is incorrect');
        }
    }

    public static function register(Request $request)
    {

        $faker = Faker::create();

        return User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'api_token' => str_random(60),
            'password' => bcrypt($request->get('password')),
            'avatar' => 'default.jpg',
            'color' => $faker->hexColor(),
            'hunter' => $faker->boolean($chanceOfGettingTrue = 40),
        ]);
    }

    public static function setActive(Request $request)
    {
        if (!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();
        $user->active = true;
        $user->save();

        return $user;
    }

    public static function setInactive(Request $request)
    {
        if (!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();
        $user->active = false;
        $user->save();

        return $user;
    }

    public static function update(Request $request)
    {
        if (!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();

        /*return $request->all();*/

        if ($request->has('phone_number'))
            $user->phone_number = $request->get('phone_number');
        if ($request->file('avatar')) {
            $file = $request->file('avatar');
            $file->move(public_path() . '/images/', $user->id . '.jpg');
            $user->avatar = '/images/' . $user->id . '.jpg';
        }
        if ($request->has('first_name'))
            $user->first_name = $request->get('first_name');
        if ($request->has('last_name'))
            $user->last_name = $request->get('last_name');

        if ($request->has('new_password')) {
            if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('old_password')])) {
                $user->password = bcrypt($request->get('new_password'));
            } else {
                throw new ErrorException("The password provided does not match an existing password for this user");
            }
        }
        $user->save();

        return $user;
    }

    public static function getAllUsers(Request $request)
    {
        return User::all();
    }

    public static function getUser(Request $request)
    {

        if (!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $api_token = $request->header('api');
        return User::where('api_token', $api_token)->first();

    }

    public static function getOtherUser(Request $request, $id)
    {
        if (!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        return User::where('id', $id)->first();
    }

    public static function addPoints(Request $request)
    {
        if (!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();

        if ($request->has('points')) {
            $user->points += $request->get('points');
        } else {
            throw new MissingMandatoryParametersException('You need to provide points');
        }

        $user->save();

        return $user;

    }

    public static function subtractPoints(Request $request)
    {
        if (!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();

        if ($request->has('points')) {
            $user->points -= $request->get('points');
        } else {
            throw new MissingMandatoryParametersException('You need to provide points');
        }

        $user->save();

        return $user;
    }
}