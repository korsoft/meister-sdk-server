<?php

namespace App\Http\Middleware;

use Closure;

use App\User;

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
        if($request->user()->type < User::TYPE_SYSTEM_INTEGRATOR)
            throw new Exception("Permission denied", 1);
        
        return $next($request);
    }
}
