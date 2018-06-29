<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class LogRequests extends Model
{

    protected $table = 'log_requests';
    
    const TYPE_TREE_REQUEST = 1;
    const TYPE_ENDPOINT_REQUEST = 2;
    
    const NO_EXCEPTION = 1;
    const JSON_EXCEPTION= 2;
    const FORMAT_EXCEPTION= 3;
    const CLIENT_EXCEPTION= 4;
    const OTHER_EXCEPTION= 5;
    const GATEWAY_DOES_NOT_EXISTS= 6;

    
    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = [
        'id','user_id','body','exception_type','request_type','created_at','updated_at'
    ];
    
    protected $dates= [
        'created_at','updated_at'
    ];
    
    public function user (){
        return $this->hasOne('App\User');
    }

}
