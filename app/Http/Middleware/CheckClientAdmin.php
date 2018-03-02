<?php

namespace App\Http\Middleware;

use Closure;

use App\User;

class CheckClientAdmin
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
        if($request->user()->type < User::TYPE_CLIENT_ADMIN)
            throw new Exception("Permission denied", 1);
            
        return $next($request);
    }
}
