<?php

//namespace kcfinder\integration;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

class LaravelAdministrator {
    protected static $authenticated = false;
    
    static function runIntegration() {
        $laravelPath = __DIR__ . "/../../../../..";

        if(file_exists($laravelPath . '/vendor/autoload.php') && file_exists($laravelPath . '/bootstrap/app.php')) {
            $currentCwd = getcwd();
            require $laravelPath.'/vendor/autoload.php';
            
            $_SERVER["REQUEST_METHOD"] = "GET";
            
            $app = require_once $laravelPath.'/bootstrap/app.php';
            $kernel = $app->make(Kernel::class);
            
            $response = $kernel->handle(
                $request = Request::capture()
            );
            
            if(Auth::guard("backend")->check())
                return true;
        }
        
        return false;
    }
}
