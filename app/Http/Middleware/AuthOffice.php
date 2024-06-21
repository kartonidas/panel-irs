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

        return $next($request);
    }
}