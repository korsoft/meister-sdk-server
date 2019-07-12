<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientUserRole extends Model
{
    /**
     * @var table Table to reference
     */
    protected $table = 'client_user_role';

    /**
     * The attributes that are mass assignable. For now, 'user_id','client_id','role_id','default' are the columns mapped to this object
     *
     * @var array
     */
    protected $fillable = [
        'user_id','client_id','role_id','default'
    ];

     /**
     * The attributes that are cast to a given type. For now 'user_id' => 'integer','client_id' => 'integer','role_id' => 'integer','default' => 'integer'
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'client_id' => 'integer',
        'role_id' => 'integer',
        'default' => 'integer'
    ];

     /**
     * The attributes that are Dates. For now 'created_at','updated_at'
     *
     * @var array
     */
    protected $dates = [
        'created_at','updated_at'
    ];

    /**
     * Return the client related yo this Relation using $object->client
     */
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    /**
     * Return the role related yo this Relation using $object->role
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * Return the user related yo this Relation using $object->user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
