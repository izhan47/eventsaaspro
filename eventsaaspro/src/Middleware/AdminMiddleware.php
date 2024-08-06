<?php namespace Eventsaaspro\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware {

    public function handle($request, Closure $next)
    {
        if(Auth::check() && Auth::user()->hasRole('admin') )
        {
            return redirect()->route('voyager.dashboard');
        }
        return $next($request);
    }
}
