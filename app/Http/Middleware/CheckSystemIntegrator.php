<?php

namespace App\Http\Middleware;

use Closure;

use App\User;
use Exception;

/**
 * Validates Each request to be called by System Integrator user.
 */
class CheckSystemIntegrator
{
    /**
    * Check if user logged is not of System Integrator Type, then it trows an Exception.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * 
    */
    public function handle($request, Closure $next)
    {
        if($request->user()->type < User::TYPE_SYSTEM_INTEGRATOR)
            throw new Exception("Permission denied", 1);
        
        return $next($request);
    }
}
