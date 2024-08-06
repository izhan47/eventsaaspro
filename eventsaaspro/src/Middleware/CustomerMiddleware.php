<?php namespace Eventsaaspro\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware {

    public function handle($request, Closure $next)
    {
        if(Auth::check() && Auth::user()->hasRole('customer') )
        {
            return $next($request);
        }

        return redirect()->route('eventsaaspro.welcome');
    }

}
