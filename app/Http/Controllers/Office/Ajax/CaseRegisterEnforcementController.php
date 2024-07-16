<?php
 
namespace App\Http\Controllers\Office\Ajax;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\View\View;

use App\Abstracts\Office\AjaxExportControllerAbstract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Office\CaseEnforcementRequest;
use App\Libraries\Helper;
use App\Models\CaseRegistry;
use App\Models\CaseRegisterEnforcement;
use App\Traits\AjaxTable;

class CaseRegisterEnforcementController extends AjaxExportControllerAbstract
{
    use AjaxTable;
    
    protected function modelName() : string
    {
        return CaseRegisterEnforcement::class;
    }
    
    public function exportBaseName() : string
    {
        return __("postępowania egzekucyjne");
    }
    
    public function list(Request $request, $id, $export = false)
    {
        $params = $this->getAjaxTableParams($request);
        $sort = $this->getSortOrder($request, false);
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        $enforcements = $case->enforcements()->select("case_register_enforcements.*");
        
        switch($sort[0])
        {
            case "execution_status_id":
                $enforcements->leftJoin("dictionaries", "dictionaries.id", "=", "case_register_enforcements.execution_status_id");
                $sort[0] = "dictionaries.value";
            break;
        }
        
        if(!empty($params["filter"]["signature"]))
            $enforcements->where("signature", "LIKE", "%" . $params["filter"]["signature"] . "%");
        if(!empty($params["filter"]["execution_status_id"]))
            $enforcements->where("execution_status_id", $params["filter"]["execution_status_id"]);
        if(!empty($params["filter"]["date_from"]))
            $enforcements->where("date", ">=", $params["filter"]["date_from"]);
        if(!empty($params["filter"]["date_to"]))
            $enforcements->where("date", "<=", $params["filter"]["date_to"]);
        
        $enforcements->orderBy($sort[0], $sort[1]);
        $maxRows = $enforcements->count();

        $enforcements = !$export ? $enforcements->paginate($params["topRecords"]) : $enforcements->get();

        $vData = [
            "case" => $case,
            "enforcements" => $enforcements,
        ];

        if($export)
            return view("office.case-register.export.enforcement-table", $vData);
        
        $view = view("office.case-register.table.enforcement-table", $vData);
        
        $out = [
            "table" => $view->render(),
            "maxrows" => $maxRows,
            "paginator" => $enforcements->render("office.partials.pagination")->toHtml()
        ];
        
		return $out;
    }
        
    public function getEnforcement(Request $request, $id, $eid)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        $enforcement = CaseRegisterEnforcement::find($eid);
        if(!$enforcement)
            throw new Exception(__("Postępowanie nie istnieje"));
        
        return [
            "data" => $enforcement
        ];
    }
    
    public function EnforcementPost(CaseEnforcementRequest $request, $id)
    {
        $validated = $request->validated();
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        if(!empty($validated["id"]))
        {
            $enforcement = CaseRegisterEnforcement::find($validated["id"]);
            if(!$enforcement)
                throw new Exception(__("Postępowanie nie istnieje"));
            
            $enforcement->signature = $validated["signature"];
            $enforcement->baliff = $validated["baliff"];
            $enforcement->baliff_street = $validated["baliff_street"];
            $enforcement->baliff_zip = $validated["baliff_zip"];
            $enforcement->baliff_city = $validated["baliff_city"];
            $enforcement->execution_status_id = $validated["execution_status_id"];
            $enforcement->date = $validated["date"];
            $enforcement->cost_representation_execution_proceedings = $validated["cost_representation_execution_proceedings"] ?? null;
            $enforcement->enforcement_costs = $validated["enforcement_costs"] ?? null;
            $enforcement->date_against_payment = $validated["date_against_payment"] ?? null;
            $enforcement->date_ineffective = $validated["date_ineffective"] ?? null;
            $enforcement->date_another_redemption = $validated["date_another_redemption"] ?? null;
            $enforcement->save();
        }
        else
        {
            $enforcement = new CaseRegisterEnforcement;
            $enforcement->case_registry_id = $case->id;
            $enforcement->signature = $validated["signature"];
            $enforcement->baliff = $validated["baliff"];
            $enforcement->baliff_street = $validated["baliff_street"];
            $enforcement->baliff_zip = $validated["baliff_zip"];
            $enforcement->baliff_city = $validated["baliff_city"];
            $enforcement->execution_status_id = $validated["execution_status_id"];
            $enforcement->date = $validated["date"];
            $enforcement->cost_representation_execution_proceedings = $validated["cost_representation_execution_proceedings"] ?? null;
            $enforcement->enforcement_costs = $validated["enforcement_costs"] ?? null;
            $enforcement->date_against_payment = $validated["date_against_payment"] ?? null;
            $enforcement->date_ineffective = $validated["date_ineffective"] ?? null;
            $enforcement->date_another_redemption = $validated["date_another_redemption"] ?? null;
            $enforcement->save();
        }
        
        return ["success" => true];
    }
    
    public function deleteEnforcement(Request $request, $id, $eid)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        $enforcement = CaseRegisterEnforcement::find($eid);
        if(!$enforcement)
            throw new Exception(__("Postępowanie nie istnieje"));
        
        $enforcement->delete();
        
        return ["success" => true];
    }
}