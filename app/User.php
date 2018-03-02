<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const TYPE_SYSTEM_ADMIN = 99;
    const TYPE_CLIENT_ADMIN = 49;
    const TYPE_CLIENT_USER = 29;

    const DEFAULT_PASSWORD = 'A123456789';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email','first_name','last_name','type','client_id'
    ];

    public function client(){
        return $this->belongsTo('App\Client', 'client_id');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
