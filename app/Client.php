<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Client model
 *
 * 
 */
class Client extends Model
{
	/**
     * The table associated with the model. In this case 'client'
     *
     * @var string
     */
    protected $table = 'client';

  
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates. For now only 'deleted_at']
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable. For now, 'name', 'sap_number', 'created_at','updated_at' are the columns mapped to this object
     *
     * @var array
     */
    protected $fillable = array('name', 'sap_number', 'created_at','updated_at');


    /**
     * The users related to Client. Can be accessed by $object->users()
     */
    public function users(){
        return $this->belongsToMany('App\User');
    }    
}

