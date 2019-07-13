<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{
    protected $table = 'talents';

    public function users()
    {
        return $this->belongsToMany(User::class, 'talent_user', 'talent_id')->withTrashed();
    }
}
