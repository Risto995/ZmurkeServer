<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Game;
use App\Location;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;

class GameController extends Controller
{
    public static function getCurrentGame(Request $request){
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();

        return $user->game()->get();
    }

    public static function getPlayersInCurrentGame(Request $request){
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();
        $game = $user->game()->first();
        return Game::find($game['id'])->players()->get();
    }

    public static function createNewGame(Request $request){

        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();

        $game = new Game();
        $game->number_of_players = $request->get('number_of_players');
        $game->time_limit = $request->get('time_limit');
        $game->starting_point = $user->current_location;
        $game->created_by = $user->id;
        $game->save();

        $user->current_game = $game->id;
        $user->hunter = true;
        $user->save();

        $players = array();
        $strings_array = explode(',', $request->get('players'));

        foreach ($strings_array as $each_number) {
            $players[] = (int) $each_number;
        }

        foreach ($players as $player_id){
            $player = User::find($player_id);
            $player->current_game = $game->id;
            $player->save();
        }

        return $game;

    }

    public static function endGame(Request $request){
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();
        $game = Game::where('id', $user->current_game)->first();
        $players = User::where('current_game', $game->id)->get();

        $maxPoints = User::where('current_game', $game->id)->max('points');
        $winner = User::where('current_game', $game->id)->where('points', $maxPoints)->first();
        $game->winner = $winner->id;
        $game->save();

        foreach ($players as $player){
            $pl = User::where('id', $player->id)->first();
            $pl->points = 0;
            $pl->save();
        }

        return $winner;
    }

    public static function getWinner(Request $request){
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');

        $user = User::where('api_token', $request->header('api'))->first();
        $game = Game::where('id', $user->current_game)->first();

        return User::where('id', $game->winner)->first();

    }
}
