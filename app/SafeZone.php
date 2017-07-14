<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SafeZone extends Model
{
    /**
     * Get the user that owns this safe zone
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get the start location for the safe zone
     */
    public function startLocation()
    {
        return $this->belongsTo('App\Location', 'start_location');
    }


    /**
     * Get the end location for the safe zone
     */
    public function endLocation()
    {
        return $this->belongsTo('App\Location', 'end_location');
    }
}
