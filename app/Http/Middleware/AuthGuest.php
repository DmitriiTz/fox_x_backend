<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthGuest
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

        if(!auth()->check()) {
            Auth::loginUsingId(4);
        }
        return $next($request);
    }
}
