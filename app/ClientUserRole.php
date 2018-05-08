<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientUserRole extends Model
{
    protected $table = 'client_user_role';

    protected $fillable = [
        'user_id','client_id','role_id','default'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'client_id' => 'integer',
        'role_id' => 'integer',
        'default' => 'integer'
    ];

    protected $dates = [
        'created_at','updated_at'
    ];

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
