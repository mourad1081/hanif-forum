<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class VipAccess
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
        if (Auth::check() and Auth::user()->isAtLeastVIP())
            return $next($request);
        else
            return redirect('login');
    }
}
