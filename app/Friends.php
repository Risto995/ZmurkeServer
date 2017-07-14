<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{
    protected $fillable = [
        'first_user', 'second_user'
    ];

    public function firstUser()
    {
        return $this->belongsTo('App\User', 'first_user');
    }

    public function secondUser()
    {
        return $this->belongsTo('App\User', 'second_user');
    }
}
