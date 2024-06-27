<?php
 
namespace App\Http\Controllers\Office;
 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Http\Requests\Office\LoginRequest;
use App\Models\OfficeUser;
use App\Models\UserLoginHistory;
 
class LoginController
{
    public function login(): View|RedirectResponse
    {
        if(Auth::guard("office")->check())
            return redirect()->route("office");
        
        return view("office.login.form");
    }
    
    public function loginPost(LoginRequest $request): RedirectResponse
    {
        if(Auth::guard("office")->check())
            return redirect()->route("office");
        
        $credentials = [
			"email" => $request->input("email"),
			"password" => $request->input("password"),
			"active" => 1,
            "block" => 0,
		];

        if (Auth::guard("office")->attempt($credentials))
        {
            $user = Auth::guard("office")->user();
            $user->last_login = time();
            $user->last_activity = time();
            $user->save();
            
            UserLoginHistory::log($request, $credentials["email"], UserLoginHistory::SOURCE_OFFICE, true);
            $request->session()->regenerate();
            return redirect()->route("office");
        }
        else
        {
            UserLoginHistory::log($request, $credentials["email"], UserLoginHistory::SOURCE_OFFICE, false);
            $user = OfficeUser::where("email", $credentials["email"])->first();
            if($user && $user->block)
                return redirect()->back()->withErrors(__("Konto zostało zablokowane. Prosimy o kontakt z Kancelarią Radców Prawnych Ryszewski Szubierajski sp.k."))->withInput();
        }

        return redirect()->back()->withErrors(__("Nieprawidłowe dane logowanie"))->withInput();
    }
    
    public function logout(): RedirectResponse
    {
        Auth::guard("office")->logout();
    	return redirect()->route("office");
    }
    
    public function checkActivity()
    {
        $status = true;
        if(!Auth::guard("office")->check() || Auth::guard("office")->user()->isActivityTimeout())
            $status = false;
        return ["status" => $status];
    }
}