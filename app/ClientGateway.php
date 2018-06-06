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
    const AUTH_TYPE_OAUTH_2 = 4; 
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_gateway';

    const URL_GENERIC_PATH = '/sap/opu/odata/MEISTER/ENGINE/Execute';
    const ENDPOINT_LOOKUP = 'Meister.SDK.Lookup';
    const ENDPOINT_MANAGER = 'Meister.Endpoint.Manager';

  
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

     protected $casts = [
        'auth_type' => 'integer'
    ];

    protected $fillable = array('name','url','username','password','auth_type','digest', 'consumer_key', 'consumer_secret','token','token_secret','client_id_for_oauth2','client_secret_for_oauth2', 'auth_url_for_oauth2','created_at','updated_at');
    
     public function clients()
    {
        return $this->hasMany('App\ClientGatewayRelation','gateway_id');
    }
}
