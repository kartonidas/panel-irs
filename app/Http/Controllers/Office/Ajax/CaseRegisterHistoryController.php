<?php
 
namespace App\Http\Controllers\Office\Ajax;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\View\View;

use App\Abstracts\Office\AjaxExportControllerAbstract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Office\CaseHistoryRequest;
use App\Libraries\Helper;
use App\Models\CaseRegistry;
use App\Models\CaseRegisterHistory;
use App\Models\OfficeUser;
use App\Traits\AjaxTable;

class CaseRegisterHistoryController extends AjaxExportControllerAbstract
{
    use AjaxTable;
    
    protected function modelName() : string
    {
        return CaseRegisterHistory::class;
    }
    
    public function exportBaseName() : string
    {
        return __("historia czynności");
    }
    
    public function list(Request $request, $id, $export = false)
    {
        $params = $this->getAjaxTableParams($request);
        $sort = $this->getSortOrder($request, false);
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $histories = $case->histories()->select("case_register_histories.*");
        
        if(!empty($params["filter"]["history_action_id"]))
            $histories->where("history_action_id", $params["filter"]["history_action_id"]);
        if(!empty($params["filter"]["date_from"]))
            $histories->where("date", ">=", $params["filter"]["date_from"]);
        if(!empty($params["filter"]["date_to"]))
            $histories->where("date", "<=", $params["filter"]["date_to"]);
            
        if($sort[0] == "history_action_id")
        {
            $histories->leftJoin("dictionaries", "dictionaries.id", "=", "case_register_histories.history_action_id");
            $sort[0] = "dictionaries.value";
        }
        
        $histories->orderBy($sort[0], $sort[1]);
        $maxRows = $histories->count();

        $histories = !$export ? $histories->paginate($params["topRecords"]) : $histories->get();

        $vData = [
            "case" => $case,
            "histories" => $histories,
        ];

        if($export)
            return view("office.case-register.export.history-table", $vData);
        
        $view = view("office.case-register.table.history-table", $vData);
        
        $out = [
            "table" => $view->render(),
            "maxrows" => $maxRows,
            "paginator" => $histories->render("office.partials.pagination")->toHtml()
        ];
        
		return $out;
    }
        
    public function getHistory(Request $request, $id, $hid)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $history = CaseRegisterHistory::find($hid);
        if(!$history)
            throw new Exception(__("Czynność nie istnieje"));
        
        return [
            "data" => $history
        ];
    }
    
    public function historyPost(CaseHistoryRequest $request, $id)
    {
        $validated = $request->validated();
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        if(!empty($validated["id"]))
        {
            $history = CaseRegisterHistory::find($validated["id"]);
            if(!$history)
                throw new Exception(__("Czynność nie istnieje"));
            
            $history->date = $validated["date"];
            $history->history_action_id = $validated["history_action_id"];
            $history->description = $validated["description"];
            $history->save();
        }
        else
        {
            $history = new CaseRegisterHistory;
            $history->case_registry_id = $case->id;
            $history->date = $validated["date"];
            $history->history_action_id = $validated["history_action_id"];
            $history->description = $validated["description"];
            $history->save();
        }
        
        return ["success" => true];
    }
    
    public function deleteHistory(Request $request, $id, $hid)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $history = CaseRegisterHistory::find($hid);
        if(!$history)
            throw new Exception(__("Czynność nie istnieje"));
        
        $history->delete();
        
        return ["success" => true];
    }
}