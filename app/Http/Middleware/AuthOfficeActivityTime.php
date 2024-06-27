<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;
use DateInterval;
use DateTime;

class AuthOfficeActivityTime
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard("office")->user();
        if($user)
        {
            if($user->isActivityTimeout())
            {
                Auth::guard("office")->logout();
                return redirect()->route("office");
            }
            
            $user->last_activity = time();
            $user->saveQuietly();
        }
        
        return $next($request);
    }
}