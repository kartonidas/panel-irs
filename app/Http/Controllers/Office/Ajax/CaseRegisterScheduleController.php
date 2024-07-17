<?php
 
namespace App\Http\Controllers\Office\Ajax;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\View\View;

use App\Abstracts\Office\AjaxExportControllerAbstract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Office\CaseScheduleRequest;
use App\Libraries\Helper;
use App\Models\CaseRegistry;
use App\Models\CaseRegisterSchedule;
use App\Models\OfficeUser;
use App\Traits\AjaxTable;

class CaseRegisterScheduleController extends AjaxExportControllerAbstract
{
    use AjaxTable;
    
    protected function modelName() : string
    {
        return CaseRegisterSchedule::class;
    }
    
    public function exportBaseName() : string
    {
        return __("harmonogram");
    }
    
    public function list(Request $request, $id, $export = false)
    {
        $params = $this->getAjaxTableParams($request);
        $sort = $this->getSortOrder($request, false);
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $schedules = $case->schedules();
        
        if(!empty($params["filter"]["date_from"]))
            $schedules->where("date", ">=", $params["filter"]["date_from"]);
        if(!empty($params["filter"]["date_to"]))
            $schedules->where("date", "<=", $params["filter"]["date_to"]);
            
        $schedules->orderBy($sort[0], $sort[1]);
        $maxRows = $schedules->count();

        $schedules = !$export ? $schedules->paginate($params["topRecords"]) : $schedules->get();

        $vData = [
            "case" => $case,
            "schedules" => $schedules,
        ];
        
        if($export)
            return view("office.case-register.export.schedule-table", $vData);

        $view = view("office.case-register.table.schedule-table", $vData);
        
        $out = [
            "table" => $view->render(),
            "maxrows" => $maxRows,
            "paginator" => $schedules->render("office.partials.pagination")->toHtml()
        ];
        
		return $out;
    }
        
    public function getSchedule(Request $request, $id, $hid)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $schedule = CaseRegisterSchedule::find($hid);
        if(!$schedule)
            throw new Exception(__("Harmonogram nie istnieje"));
        
        return [
            "data" => $schedule
        ];
    }
    
    public function SchedulePost(CaseScheduleRequest $request, $id)
    {
        $validated = $request->validated();
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        if(!empty($validated["id"]))
        {
            $schedule = CaseRegisterSchedule::find($validated["id"]);
            if(!$schedule)
                throw new Exception(__("Harmonogram nie istnieje"));
            
            $schedule->date = $validated["date"];
            $schedule->amount = $validated["amount"];
            $schedule->save();
        }
        else
        {
            $schedule = new CaseRegisterSchedule;
            $schedule->case_registry_id = $case->id;
            $schedule->date = $validated["date"];
            $schedule->amount = $validated["amount"];
            $schedule->save();
        }
        
        return ["success" => true];
    }
    
    public function deleteSchedule(Request $request, $id, $hid)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $schedule = CaseRegisterSchedule::find($hid);
        if(!$schedule)
            throw new Exception(__("Harmonogram nie istnieje"));
        
        $schedule->delete();
        
        return ["success" => true];
    }
}