<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientGatewayRelation extends Model
{

    
    /**
     * The table associated with the model. In this case 'client_gateway_relation'
     *
     * @var string
     */
    protected $table = 'client_gateway_relation';

    
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates. For now 'deleted_at'
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

     /**
     * The attributes that are cast to a given type. For now, 'gateway_id' => 'integer','client_id' => 'integer'
     *
     * @var array
     */
     protected $casts = [
        'gateway_id' => 'integer',
        'client_id' => 'integer'
    ];

    /**
     * The attributes that are mass assignable. For now, 'client_id','gateway_id' are the columns mapped to this object
     *
     * @var array
     */
    protected $fillable = array('client_id','gateway_id');

    /**
     * Return the client related with this record using $object->client
     */
    public function client(){
        return $this->belongsTo('App\Client', 'client_id');
    }  
    

    /**
     * Return the gateway related with this record using $object->gateway
     */
    public function gateway(){
        return $this->belongsTo('App\ClientGateway', 'gateway_id');
    }  
}
