<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientGatewayRelation extends Model
{

    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_gateway_relation';

    
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

     protected $casts = [
        'client_gateway_id' => 'integer',
        'client_id' => 'integer'
    ];

    protected $fillable = array('client_id','client_gateway_id');

    public function client(){
        return $this->belongsTo('App\Client', 'client_id');
    }  
    

    public function gateway(){
        return $this->belongsTo('App\ClientGateway', 'gateway_id');
    }  
}
