<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class AuthPanel
{
    public function handle($request, Closure $next)
    {
    	if(!Auth::check())
			return redirect()->route("login");

        if(Auth::user()->block)
        {
            Auth::logout();
            return redirect()->route("login");
        }
            
        return $next($request);
    }
}