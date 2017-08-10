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
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Auth;

class UsersController extends Controller
{

    public static function login(Request $request){
        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            // Authentication passed...
            $user = Auth::user();
            return $user['api_token'];
        } else {
            throw new AccessDeniedException('Email or password is incorrect');
        }
    }

    public static function register(Request $request){

        return User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'api_token' => str_random(60),
            'password' => bcrypt($request->get('password')),
            'avatar' => 'default.jpg',
        ]);
    }

    public static function update(Request $request){
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();

        if($request->has('phone_number'))
            $user->phone_number = $request->get('phone_number');
        if($request->has('avatar'))
            $user->avatar = $request->get('avatar');
        if($request->has('first_name'))
            $user->first_name = $request->get('first_name');
        if($request->has('last_name'))
            $user->last_name = $request->get('last_name');

        if($request->has('new_password')){
            if(Auth::attempt(['email' => $request->get('email'), 'old_password' => $request->get('password')])){
                $user->password = bcrypt($request->get('new_password'));
            } else {
                throw new ErrorException("The password provided does not match an existing password for this user");
            }
        }



        $user->save();

        return $user;
    }

    public static function getAllUsers(Request $request){
        return User::all();
    }

    public static function getUser(Request $request){

        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $api_token = $request->header('api');
        return User::where('api_token', $api_token)->first();

    }

    public static function getOtherUser(Request $request, $id){
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        return User::where('id', $id)->first();
    }
}