<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Filesystem\Cache;
use Illuminate\Support\Facades\Auth;

class Online
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
        if(Auth::check() && Auth::user()->id != 4) {
           \Illuminate\Support\Facades\Cache::put('users.'.Auth::user()->id, true, 1);
        }

        return $next($request);
    }
}
