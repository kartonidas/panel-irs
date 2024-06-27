<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class AuthOffice
{
    public function handle($request, Closure $next)
    {
    	if(!Auth::guard("office")->check())
			return redirect()->route("office.login");

        if(Auth::guard("office")->user()->block)
        {
            Auth::guard("office")->logout();
            return redirect()->route("office");
        }
            
        return $next($request);
    }
}