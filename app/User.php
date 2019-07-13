<?php

namespace App;

use App\Notifications\MyResetPassword;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable,SoftDeletes,SoftCascadeTrait;

    protected $table = 'users';
    protected $dates = ['deleted_at'];
    protected $softCascade = ['publications','comments','likes'];

    const USER_ADMINISTRATOR = 'true';
    const USER_ARTIST = 'false';
    const USER_ACTIVE = 'activo';
    const USER_INACTIVE = 'inactivo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'talent_type_id',
        'name',
        'surname',
        'birthday',
        'gender',
        'phone',
        'email',
        'profile_photo',
        'status',
        'password',
        'administrator',
        'is_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }  

    public function setNameAttribute($entry)
    {
        $this->attributes['name'] = strtolower($entry);
    }

    public function setEmailAttribute($entry)
    {
        $this->attributes['email'] = strtolower($entry);
    }

    public function getNameAttribute($entry)
    {
        return ucwords($entry);
    }

    public function isAdministrator()
    {
        return $this->administrator == User::USER_ADMINISTRATOR;
    }

    public function isActive()
    {
        return $this->status == User::USER_ACTIVE;
    }

    public function isInactive()
    {
        return $this->status == User::USER_INACTIVE;
    }

    public function isVerified()
    {
        return $this->is_verified == 1;
    }

    public function isNotVerified()
    {
        return $this->is_verified == 0;
    }

    public function publications()
    {
        return $this->hasMany('App\Publication')->withTrashed();
    }

    public function comments()
    {
        return $this->hasMany('App\Comment')->withTrashed();
    }
    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function followers()
    {
        return $this->hasMany('App\Follower','user_id');
    }

    public function followings()
    {
        return $this->hasMany('App\Follower','follower_id');
    }    
    
    public function talents()
    {
        return $this->belongsToMany(Talent::class, 'talent_user', 'user_id')->withPivot('talent_id', 'user_id');
    }      
}
