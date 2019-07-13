<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{

    use SoftDeletes;

    protected $table = 'notifications';
    protected $dates = ['deleted_at'];

    const NOTIFICATION_PUBLISHED = 'Publicada';
    const NOTIFICATION_PENDING = 'Pendiente';

    protected $fillable = [
        'user_id',
        'notification_type_id',
        'detail',
        'state',
    ];

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function actualState()
    {
        return $this->state==Notification::NOTIFICATION_PUBLISHED;
        //si retora true: esta publicada, de lo contrario esta pendiente
    }
}
