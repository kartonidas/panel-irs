<?php
 
namespace App\Http\Controllers\Panel;
 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Http\Requests\Office\LoginRequest;
use App\Models\User;
use App\Models\UserLoginHistory;

class LoginController
{
    public function login(): View|RedirectResponse
    {
        if(Auth::check())
            return redirect()->route("panel");
        
        return view("panel.login.form");
    }
    
    public function loginPost(LoginRequest $request): RedirectResponse
    {
        if(Auth::check())
            return redirect()->route("panel");
        
        $credentials = [
			"email" => $request->input("email"),
			"password" => $request->input("password"),
			"active" => 1,
		];

        if (Auth::attempt($credentials))
        {
            $user = Auth::user();
            $user->last_login = time();
            $user->save();
            
            UserLoginHistory::log($request, $credentials["email"], UserLoginHistory::SOURCE_SITE, true);
            $request->session()->regenerate();
			return redirect()->route("panel");
        }
        else
        {
            UserLoginHistory::log($request, $credentials["email"], UserLoginHistory::SOURCE_SITE, false);
            $user = User::where("email", $credentials["email"])->first();
            if($user && $user->block)
                return redirect()->back()->withErrors(__("Konto zostało zablokowane. Prosimy o kontakt z Kancelarią Radców Prawnych Ryszewski Szubierajski sp.k."))->withInput();
        }

        return redirect()->back()->withErrors(__("Nieprawidłowe dane logowanie"))->withInput();
    }
    
    public function logout(): RedirectResponse
    {
        Auth::logout();
    	return redirect()->route("login");
    }
}