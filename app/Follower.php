<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    protected $table = 'followers';

    /*public function users()
    {
        return $this->hasMany('App\User', 'id')->withTrashed();
    }*/

    public function user(){
    	return $this->belongsTo('App\User','user_id')->withTrashed();
    }

    public function follower(){
    	return $this->belongsTo('App\User','follower_id')->withTrashed();
    }
}
