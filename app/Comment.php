<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = 'comments';
    protected $dates = ['deleted_at'];

    const COMMENT_ACTIVE = 'true';
    const COMMENT_INACTIVE = 'false';


    protected $fillable = [
        'user_id',
        'publication_id',
        'content',
        'status'
    ];

    public function isActive()
    {
        return $this->status == Comment::COMMENT_ACTIVE;
    }
    
    public function isInactive()
    {
        return $this->status == Comment::COMMENT_INACTIVE;
    }

    public function publications()
    {
        return $this->belongsTo('App\Publication', 'publication_id')->withTrashed();
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id')->withTrashed();
    }
}
