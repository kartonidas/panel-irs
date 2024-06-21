<?php
 
namespace App\Http\Controllers\Panel;
 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Http\Requests\Office\LoginRequest;
 
class LoginController
{
    public function login(): View|RedirectResponse
    {
        if(Auth::check())
            return redirect()->route("office");
        
        return view("panel.login.form");
    }
    
    public function loginPost(LoginRequest $request): RedirectResponse
    {
        if(Auth::check())
            return redirect()->route("office");
        
        $credentials = [
			"email" => $request->input("email"),
			"password" => $request->input("password"),
			"active" => 1,
		];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
			return redirect()->route("office");
        }

        return redirect()->back()->withErrors(__("Nieprawidłowe dane logowanie"))->withInput();
    }
    
    public function logout(): RedirectResponse
    {
        Auth::logout();
    	return redirect()->route("login");
    }
}