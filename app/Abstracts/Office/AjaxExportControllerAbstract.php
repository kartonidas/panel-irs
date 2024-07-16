<?php

namespace App\Abstracts\Office;

use Exception;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\CaseRegistry;
use App\Traits\Export;

abstract class AjaxExportControllerAbstract extends Controller {
    use Export;
    
    abstract public function list(Request $request, $id, $export = false);
    abstract public function exportBaseName() : string;
    
    public function export(Request $request, $id) : array
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        $xml = $this->list($request, $id, true);
        $uuid = $this->xlsxOffice($xml, $this->prepareCaseExportName($case, $this->exportBaseName()) . ".xlsx");
        
        return ["url" => route("office.export", $uuid)];
    }
}
