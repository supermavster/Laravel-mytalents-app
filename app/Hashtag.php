<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hashtag extends Model
{
    use SoftDeletes;

    protected $table = 'hashtags';
    protected $dates = ['deleted_at'];

    public function publications()
    {
        return $this->belongsTo('App\Publication', 'publication_id');
    }
}
