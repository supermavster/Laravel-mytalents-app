<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Publication extends Model
{
    use SoftDeletes,SoftCascadeTrait;

    protected $table = 'publications';
    protected $dates = ['deleted_at'];
    protected $softCascade = ['comments','likes'];

    const PUBLICATION_ACTIVE = 'activo';
    const PUBLICATION_INACTIVE = 'inactivo';

    protected $fillable = [
        'user_id',
        'description',
        'media_file',
        'status'
    ];

    public function isActive()
    {
        return $this->state == Publication::PUBLICATION_ACTIVE;
    }
    
    public function isInactive()
    {
        return $this->state == Publication::PUBLICATION_INACTIVE;
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id')->withTrashed();
    }

    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment')->withTrashed();
    }
     
    public function hashtags()
    {
        return $this->hasMany('App\Hashtag');
    }
}
