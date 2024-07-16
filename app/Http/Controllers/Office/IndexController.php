<?php
 
namespace App\Http\Controllers\Office;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\View\View;

use App\Libraries\Helper;
use App\Models\Export;

class IndexController
{
    public function dashboard()
    {
        return redirect()->route("office.case_register");
    }
    
    public function export(Request $request, string $uuid)
    {
        $exportRow = Export::find($uuid);
        
        if(!$exportRow || $exportRow->source != Export::SOURCE_OFFICE || $exportRow->user_id != Auth::guard("office")->user()->id)
            abort(404);
        
        $file = storage_path($exportRow->file);
        if(file_exists($file))
        {
            $filename = $exportRow->filename;
            $exportRow->deleteQuietly();
            
            return response()->download($file, $filename)->deleteFileAfterSend();
        }
        
        abort(404);
    }
}