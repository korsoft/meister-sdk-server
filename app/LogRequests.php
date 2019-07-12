<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class LogRequests extends Model
{

    protected $table = 'log_requests';
    
    const TYPE_TREE_REQUEST = 1;
    const TYPE_ENDPOINT_REQUEST = 2;
    const TYPE_TEST_CONNECTION_REQUEST = 3;
    
    const NO_EXCEPTION = 1;
    const JSON_EXCEPTION= 2;
    const FORMAT_EXCEPTION= 3;
    const CLIENT_EXCEPTION= 4;
    const OTHER_EXCEPTION= 5;
    const GATEWAY_DOES_NOT_EXISTS= 6;

    
  /**
     * The attributes that are mass assignable. For now, 'email','first_name','last_name','type','client_id' are the columns mapped to this object
     *
     * @var array For now 'id','user_id','body','url','exception_type','request_type','created_at','updated_at'
     */
    protected $fillable = [
        'id','user_id','body','url','exception_type','request_type','created_at','updated_at'
    ];
    
     /**
     * The attributes that are converted to a dates. 'created_at','updated_at'
     *
     * @var array
     */
    protected $dates= [
        'created_at','updated_at'
    ];
    
    /**
     * Return the User related to LogRequest record using $object->user
     *
     */
    public function user (){
        return $this->belongsTo('App\User');
    }

}
