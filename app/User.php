<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'date_of_birth', 'api_token', 'color', 'avatar', 'points', 'current_location', 'current_game'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];


    /**
     * Get the locations for this user
     */
    public function locations()
    {
        return $this->hasMany('App\Location');
    }

    /**
     * Get the current location for this user
     */
    public function location()
    {
        return $this->belongsTo('App\Location', 'current_location');
    }

    public function friends()
    {
        return $this->belongsToMany('App\User', 'friends', 'first_user', 'second_user');
    }

    /**
     * Get the game that the user participates in.
     */
    public function game()
    {
        return $this->belongsTo('App\Game', 'current_game');
    }

    /**
     * Get the safe zone for this user
     *  */
    public function safeZone()
    {
        return $this->belongsTo('App\SafeZone', 'safe_zone');
    }
}
