<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientGateway extends Model
{

    const AUTH_TYPE_BASIC = 0;
    const AUTH_TYPE_FORM = 1;
    const AUTH_TYPE_DIGEST = 2;
    const AUTH_TYPE_OAUTH = 3;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_gateway';

  
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = array('name','url','username','password','auth_type', 'digest','created_at','updated_at');


    public function client(){
        return $this->belongsTo('App\Client', 'client_id');
    }  
    
}
