<?php
/**
 * Created by PhpStorm.
 * User: Riki
 * Date: 6/24/2017
 * Time: 6:45 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;


class Location extends Model
{

    /**
     * Get the user on that location.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }


}