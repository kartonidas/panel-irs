<?php
 
namespace App\Http\Controllers\Panel;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

use App\Http\Requests\Panel\ProfileRequest;
use App\Libraries\Helper;

class UserController
{
    public function profile(Request $request)
    {
        $user = Auth::user();
        
        $data = [
            "user" => array_merge($user->toArray(), $request->old()),
        ];
        
        return view("panel.user.profile", $data);
    }
    
    public function profilePost(ProfileRequest $request)
    {
        $validated = $request->validated();
        
        $user = DB::transaction(function () use($validated) {
            $user = Auth::user();
            
            $user->firstname = $validated["firstname"];
            $user->lastname = $validated["lastname"];
            
            if(!empty($validated["change_password"]))
                $user->password = Hash::make($validated["password"]);
            
            $user->save();
        });
        
        Helper::setMessage("profile", __("Dane zostały zaktualizowane"));
        return redirect()->back();
    }
}