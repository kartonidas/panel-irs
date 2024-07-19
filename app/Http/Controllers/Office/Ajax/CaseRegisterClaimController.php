<?php
 
namespace App\Http\Controllers\Office\Ajax;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\View\View;

use App\Abstracts\Office\AjaxExportControllerAbstract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Office\CaseClaimRequest;
use App\Libraries\Helper;
use App\Models\CaseRegistry;
use App\Models\CaseRegisterClaim;
use App\Models\OfficeUser;
use App\Traits\AjaxTable;

class CaseRegisterClaimController extends AjaxExportControllerAbstract
{
    use AjaxTable;
    
    protected function modelName() : string
    {
        return CaseRegisterClaim::class;
    }
    
    public function exportBaseName() : string
    {
        return __("roszczenia");
    }
    
    public function list(Request $request, $id, $export = false)
    {
        $params = $this->getAjaxTableParams($request);
        $sort = $this->getSortOrder($request, false);
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $claims = $case->claims();
        
        if(!empty($params["filter"]["mark"]))
            $claims->where("mark", "LIKE", "%" . $params["filter"]["mark"] . "%");
        $claims->orderBy($sort[0], $sort[1]);
        $maxRows = $claims->count();

        $claims = !$export ? $claims->paginate($params["topRecords"]) : $claims->get();

        $vData = [
            "case" => $case,
            "claims" => $claims,
        ];
        
        if($export)
            return view("office.case-register.export.claims-table", $vData);

        $view = view("office.case-register.table.claims-table", $vData);
        
        $out = [
            "table" => $view->render(),
            "maxrows" => $maxRows,
            "paginator" => $claims->render("office.partials.pagination")->toHtml()
        ];
        
		return $out;
    }
        
    public function getClaim(Request $request, $id, $cid)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $claim = CaseRegisterClaim::find($cid);
        if(!$claim)
            throw new Exception(__("Roszczenie nie istnieje"));
        
        return [
            "data" => $claim
        ];
    }
    
    public function claimPost(CaseClaimRequest $request, $id)
    {
        $validated = $request->validated();
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        if(!empty($validated["id"]))
        {
            $claim = CaseRegisterClaim::find($validated["id"]);
            if(!$claim)
                throw new Exception(__("Roszczenie nie istnieje"));
            
            $claim->amount = $validated["amount"];
            $claim->currency = $validated["currency"] ?? "PLN";
            $claim->date = $validated["date"];
            $claim->due_date = $validated["due_date"];
            $claim->mark = $validated["mark"];
            $claim->description = $validated["description"];
            $claim->save();
        }
        else
        {
            $claim = new CaseRegisterClaim;
            $claim->case_registry_id = $case->id;
            $claim->amount = $validated["amount"];
            $claim->currency = $validated["currency"] ?? "PLN";
            $claim->date = $validated["date"];
            $claim->due_date = $validated["due_date"];
            $claim->mark = $validated["mark"];
            $claim->description = $validated["description"];
            $claim->save();
        }
        
        return ["success" => true];
    }
    
    public function deleteClaim(Request $request, $id, $cid)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $claim = CaseRegisterClaim::find($cid);
        if(!$claim)
            throw new Exception(__("Roszczenie nie istnieje"));
        
        $claim->delete();
        
        return ["success" => true];
    }
}