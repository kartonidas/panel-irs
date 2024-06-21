<?php
 
namespace App\Http\Controllers\Office;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\View\View;

use App\Libraries\Helper;

class CaseRegisterController
{
    public function list(Request $request)
    {
        view()->share("activeMenuItem", "case_register");
        
        return view("office.case-register.list");
    }
}
