<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Filesystem\Cache;

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
        if(auth('api')->check() && auth('api')->user()->id != 4) {
           \Illuminate\Support\Facades\Cache::put('users.'.auth()->user()->id, true, 1);
        }

        return $next($request);
    }
}
