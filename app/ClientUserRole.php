<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientUserRole extends Model
{
    protected $table = 'client_user_role';

    protected $fillable = [
        'user_id','client_id','role_id'
    ];

    public function client()
    {
        return $this->hasOne('App\Client');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

}
