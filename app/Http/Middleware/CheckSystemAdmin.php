<?php

namespace App\Http\Middleware;

use Closure;

use Exception;
use App\User;

/**
 * Validates Each request to be called by System Admin User
 */
class CheckSystemAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user()->type < User::TYPE_SYSTEM_ADMIN)
            throw new Exception("Permission denied", 1);
        
        return $next($request);
    }
}
