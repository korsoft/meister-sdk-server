<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Role extends Model
{

    const TYPE_SYSTEM_ADMIN = 99;
    const TYPE_CLIENT_ADMIN = 49;
    const TYPE_CLIENT_USER = 29;

    protected $table = 'role';

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','value'
    ];

}
