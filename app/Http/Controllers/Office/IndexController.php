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
    
    public function filter(Request $request, $module): RedirectResponse
    {
        Helper::setFilter($request, $module);
        return redirect()->back();
    }
    
    public function clearFilter(Request $request, $module)
    {
        Helper::clearFilter($request, $module);
        return redirect()->back();
    }
    
    public function sort(Request $request, $module, $sort, $extra = null)
    {
        Helper::setSortOrder($request, $module, $sort);
        return redirect()->back();
    }
    
    public function setPageSize(Request $request, $module, $size)
    {
        Helper::setPageSize($request, $module, $size);
        return redirect()->back();
    }
}