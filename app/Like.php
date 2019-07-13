<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
    use SoftDeletes;

    protected $table = 'likes';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'publication_id'
    ];

    public function publications()
    {
        return $this->belongsTo('App\Publication', 'publication_id');
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
