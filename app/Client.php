<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client';

  
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = array('name', 'created_at','updated_at');


    public function users(){
        return $this->belongsToMany('App\User');
    }    
}

