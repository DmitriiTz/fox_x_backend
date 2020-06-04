<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;

class AuthGuest
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth('api')->user() ?? null;
        if($user !== null){
            Auth::loginUsingId($user->id);
        }
        if (!auth()->check()) {
            Auth::loginUsingId(4);
        }

        return $next($request);
    }
}
