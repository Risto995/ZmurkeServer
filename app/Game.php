<?php
/**
 * Created by PhpStorm.
 * User: Riki
 * Date: 6/24/2017
 * Time: 9:24 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * Get the game's starting point
     */
    public function startingPoint()
    {
        return $this->belongsTo('App\Location', 'starting_point');
    }

    /**
     * Get the game's creator
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * Get the users who are playing this game
     */
    public function players()
    {
        return $this->hasMany('App\User', 'current_game');
    }
}