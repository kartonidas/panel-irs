<?php
 
namespace App\Http\Controllers\Panel;
 
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Libraries\Helper;

class IndexController
{
    public function dashboard(): View
    {
        return view("panel.user.dashboard");
    }
}