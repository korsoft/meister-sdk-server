<?php

namespace App\Http\Middleware;

use Closure;

use App\User;
use Exception;


/**
 * Validates Each request to be called by Admin User
 */
class CheckClientAdmin
{
    /**
     * Check if user logged is not of System Admin Type, then it trows an Exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * 
     */
    public function handle($request, Closure $next)
    {
        if($request->user()->type < User::TYPE_CLIENT_ADMIN)
            throw new Exception("Permission denied", 1);
            
        return $next($request);
    }
}
