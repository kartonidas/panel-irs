<?php
 
namespace App\Http\Controllers\Office;
 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Libraries\Helper;

class IndexController
{
    public function dashboard()
    {
        return redirect()->route("office.case_register");
    }
}