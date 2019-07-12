<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Role extends Model
{

    const TYPE_SYSTEM_ADMIN = 99;
    const TYPE_SYSTEM_INTEGRATOR = 89;
    const TYPE_CLIENT_ADMIN = 49;
    const TYPE_CLIENT_USER = 29;

    protected $table = 'role';

    
    /**
     * The attributes that are mass assignable. For now, 'name','value' are the columns mapped to this object
     *
     * @var array
     */
    protected $fillable = [
        'name','value'
    ];

     /**
     * The attributes that are cast to a given type. For now 'value' => 'integer'
     *
     * @var array
     */
    protected $casts = [
        'value' => 'integer'
    ];

}
