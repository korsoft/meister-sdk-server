<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class the represents the model for User table.
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

     /**
     * @const TYPE_SYSTEM_ADMIN The value 99 that represenys System Admin
     */
    const TYPE_SYSTEM_ADMIN = 99;
    const TYPE_SYSTEM_INTEGRATOR = 89;
    const TYPE_CLIENT_ADMIN = 49;
    const TYPE_CLIENT_USER = 29;

    const DEFAULT_PASSWORD = 'A123456789';

   
    
    
    /**
     * The attributes that are mass assignable. For now, 'email','first_name','last_name','type','client_id' are the columns mapped to this object
     *
     * @var array
     */
    protected $fillable = [
        'email','first_name','last_name','type','client_id'
    ];

    /**
     * The attributes that are cast to a given type. For now,  'type' => 'integer','client_id' => 'integer'
     *
     * @var array
     */
    protected $casts = [
        'type' => 'integer',
        'client_id' => 'integer'
    ];

    /**
     * Overrides the object->Type attribute to return the role value
     */
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

    /**
     * Overrides the object->Clien attribute to return client Object
     */
    public function getClientAttribute($value){
        return $this->client();
    }

    /**
     * Returns the default client value
     */
    private function client(){
        return $this->clients()->where('default',true)->first();
    }

    /**
     * Returns the list of clients objects 
     */
    public function clients()
    {
        return $this->hasMany('App\ClientUserRole','user_id');
    }

    /**
     * Returns the clientRole objet based on the clientId 
     * 
     * @param  $client_id the Client id to find
     */
    public function clientRole($client_id){
        if($this->type==User::TYPE_SYSTEM_ADMIN)
        {
            return Role::where("value",User::TYPE_SYSTEM_ADMIN)->first();;
        }
        $role = $this->clients()->where('client_id',$client_id)->first();
        if($role){
            return $role->role;
        }
            
        return null;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array For now 'password', 'remember_token',
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
