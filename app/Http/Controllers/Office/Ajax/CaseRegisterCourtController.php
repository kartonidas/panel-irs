<?php
 
namespace App\Http\Controllers\Office\Ajax;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\View\View;

use App\Abstracts\Office\AjaxExportControllerAbstract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Office\CaseCourtRequest;
use App\Libraries\Helper;
use App\Models\CaseRegistry;
use App\Models\CaseRegisterCourt;
use App\Models\OfficeUser;
use App\Traits\AjaxTable;

class CaseRegisterCourtController extends AjaxExportControllerAbstract
{
    use AjaxTable;
    
    protected function modelName() : string
    {
        return CaseRegisterCourt::class;
    }
    
    public function exportBaseName() : string
    {
        return __("postępowania sądowe");
    }
    
    public function list(Request $request, $id, $export = false)
    {
        $params = $this->getAjaxTableParams($request);
        $sort = $this->getSortOrder($request, false);
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $courts = $case->courts()->select("case_register_courts.*");
        
        switch($sort[0])
        {
            case "status_id":
                $courts->leftJoin("dictionaries", "dictionaries.id", "=", "case_register_courts.status_id");
                $sort[0] = "dictionaries.value";
            break;
            case "mode_id":
                $courts->leftJoin("dictionaries", "dictionaries.id", "=", "case_register_courts.mode_id");
                $sort[0] = "dictionaries.value";
            break;
            case "court_id":
                $courts->leftJoin("courts", "courts.id", "=", "case_register_courts.court_id");
                $sort[0] = "courts.name";
            break;
        }
        
        if(!empty($params["filter"]["signature"]))
            $courts->where("signature", "LIKE", "%" . $params["filter"]["signature"] . "%");
        if(!empty($params["filter"]["status_id"]))
            $courts->where("status_id", $params["filter"]["status_id"]);
        if(!empty($params["filter"]["mode_id"]))
            $courts->where("mode_id", $params["filter"]["mode_id"]);
        if(!empty($params["filter"]["date_from"]))
            $courts->where("date", ">=", $params["filter"]["date_from"]);
        if(!empty($params["filter"]["date_to"]))
            $courts->where("date", "<=", $params["filter"]["date_to"]);
        
        $courts->orderBy($sort[0], $sort[1]);
        $maxRows = $courts->count();

        $courts = !$export ? $courts->paginate($params["topRecords"]) : $courts->get();

        $vData = [
            "case" => $case,
            "courts" => $courts,
        ];

        if($export)
            return view("office.case-register.export.court-table", $vData);
        
        $view = view("office.case-register.table.court-table", $vData);
        
        $out = [
            "table" => $view->render(),
            "maxrows" => $maxRows,
            "paginator" => $courts->render("office.partials.pagination")->toHtml()
        ];
        
		return $out;
    }
        
    public function getCourt(Request $request, $id, $cid)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $court = CaseRegisterCourt::find($cid);
        if(!$court)
            throw new Exception(__("Postępowanie nie istnieje"));
        
        return [
            "data" => $court
        ];
    }
    
    public function courtPost(CaseCourtRequest $request, $id)
    {
        $validated = $request->validated();
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        if(!empty($validated["id"]))
        {
            $court = CaseRegisterCourt::find($validated["id"]);
            if(!$court)
                throw new Exception(__("Postępowanie nie istnieje"));
            
            $court->signature = $validated["signature"];
            $court->court_id = $validated["court_id"];
            $court->department = $validated["department"];
            $court->court_street = $validated["court_street"];
            $court->court_zip = $validated["court_zip"];
            $court->court_city = $validated["court_city"];
            $court->status_id = $validated["status_id"];
            $court->mode_id = $validated["mode_id"];
            $court->date = $validated["date"];
            $court->date_enforcement = $validated["date_enforcement"] ?? null;
            $court->date_execution = $validated["date_execution"] ?? null;
            $court->cost_representation_court_proceedings = $validated["cost_representation_court_proceedings"] ?? null;
            $court->cost_representation_clause_proceedings = $validated["cost_representation_clause_proceedings"] ?? null;
            $court->code_epu_warranty = $validated["code_epu_warranty"] ?? null;
            $court->code_epu_clause = $validated["code_epu_clause"] ?? null;
            $court->code_epu_files = $validated["code_epu_files"] ?? null;
            $court->save();
        }
        else
        {
            $court = new CaseRegisterCourt;
            $court->case_registry_id = $case->id;
            $court->signature = $validated["signature"];
            $court->court_id = $validated["court_id"];
            $court->department = $validated["department"];
            $court->court_street = $validated["court_street"];
            $court->court_zip = $validated["court_zip"];
            $court->court_city = $validated["court_city"];
            $court->status_id = $validated["status_id"];
            $court->mode_id = $validated["mode_id"];
            $court->date = $validated["date"];
            $court->date_enforcement = $validated["date_enforcement"] ?? null;
            $court->date_execution = $validated["date_execution"] ?? null;
            $court->cost_representation_court_proceedings = $validated["cost_representation_court_proceedings"] ?? null;
            $court->cost_representation_clause_proceedings = $validated["cost_representation_clause_proceedings"] ?? null;
            $court->code_epu_warranty = $validated["code_epu_warranty"] ?? null;
            $court->code_epu_clause = $validated["code_epu_clause"] ?? null;
            $court->code_epu_files = $validated["code_epu_files"] ?? null;
            $court->save();
        }
        
        return ["success" => true];
    }
    
    public function deleteCourt(Request $request, $id, $hid)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $court = CaseRegisterCourt::find($hid);
        if(!$court)
            throw new Exception(__("Postępowanie nie istnieje"));
        
        $court->delete();
        
        return ["success" => true];
    }
}