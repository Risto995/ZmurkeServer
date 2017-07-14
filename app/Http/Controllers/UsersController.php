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

        if($request->has('date_of_birth'))
            $user->date_of_birth = $request->get('date_of_birth');
        if($request->has('avatar'))
            $user->avatar = $request->get('avatar');
        if($request->has('new_password') && $request->has('confirm_password') && $request->get('new_password') == $request->get('confirm_password'))
            $user->password = bcrypt($request->get('new_password'));
        else
            throw new ErrorException('Passwords do not match');

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
}