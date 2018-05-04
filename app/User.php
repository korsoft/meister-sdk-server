<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const TYPE_SYSTEM_ADMIN = 99;
    const TYPE_SYSTEM_INTEGRATOR = 89;
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

    protected $casts = [
        'type' => 'integer',
        'client_id' => 'integer'
    ];

    
    public function getTypeAttribute($value){
        if($value == User::TYPE_SYSTEM_ADMIN )
           return $value;
        else{
            $val=$this->client();
            if($val)
            {
                return $val->role->value;
            }
            else{
                return $value;
            }
        }
    }

    public function getClientAttribute($value){
        return $this->client();
    }

    private function client(){
        return $this->clients()->where('default',true)->first();
    }

    public function clients()
    {
        return $this->hasMany('App\ClientUserRole','user_id');
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
