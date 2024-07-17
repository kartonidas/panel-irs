<?php
 
namespace App\Http\Controllers\Office\Ajax;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\View\View;

use App\Abstracts\Office\AjaxExportControllerAbstract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Office\OfficeUserSelectedCaseRequest;
use App\Libraries\Helper;
use App\Models\OfficeUser;
use App\Models\OfficeUsersCaseAccess;
use App\Traits\AjaxTable;

class OfficeUserSelectedCaseAccess extends AjaxExportControllerAbstract
{
    use AjaxTable;
    
    protected function modelName() : string
    {
        return OfficeUsersCaseAccess::class;
    }
    
    public function exportBaseName() : string
    {
        return "";
    }
    
    public function list(Request $request, $id, $export = false)
    {
        OfficeUser::checkAccess("users:update");
        
        $params = $this->getAjaxTableParams($request);
        $sort = $this->getSortOrder($request, false);
        
        $user = OfficeUser::find($id);
        if(!$user)
            throw new Exception(__("Pracownik nie istnieje"));
        
        if($user->case_access_type != OfficeUser::CASE_ACCESS_SELECTED)
            throw new Exception(__("Brak zdefiniowanego dostęp do wybranych spraw"));
        
        $access = $user->caseAccess();
        
        $access->orderBy($sort[0], $sort[1]);
        $maxRows = $access->count();

        $access = $access->paginate($params["topRecords"]);

        $vData = [
            "caseSelectedAccess" => $access,
            "user" => $user,
        ];
        
        $view = view("office.users.table.case-access-table", $vData);
        
        $out = [
            "table" => $view->render(),
            "maxrows" => $maxRows,
            "paginator" => $access->render("office.partials.pagination")->toHtml()
        ];
        
		return $out;
    }
        
    public function getAccess(Request $request, $id, $aid)
    {
        OfficeUser::checkAccess("users:update");
        
        $user = OfficeUser::find($id);
        if(!$user)
            throw new Exception(__("Pracownik nie istnieje"));
        
        if($user->case_access_type != OfficeUser::CASE_ACCESS_SELECTED)
            throw new Exception(__("Brak zdefiniowanego dostęp do wybranych spraw"));
        
        $access = OfficeUsersCaseAccess::find($aid);
        if(!$access)
            throw new Exception(__("Rekord nie istnieje"));
        
        return [
            "data" => $access
        ];
    }
    
    public function accessPost(OfficeUserSelectedCaseRequest $request, $id)
    {
        OfficeUser::checkAccess("users:update");
        
        $validated = $request->validated();
        
        $user = OfficeUser::find($id);
        if(!$user)
            throw new Exception(__("Pracownik nie istnieje"));
        
        if($user->case_access_type != OfficeUser::CASE_ACCESS_SELECTED)
            throw new Exception(__("Brak zdefiniowanego dostęp do wybranych spraw"));
        
        if(!empty($validated["id"]))
        {
            $access = OfficeUsersCaseAccess::find($validated["id"]);
            if(!$access)
                throw new Exception(__("Rekord nie istnieje"));
            
            $access->customer_id = $validated["customer_id"];
            $access->type = $validated["type"];
            $access->case_numbers = $access->type == OfficeUsersCaseAccess::CASE_ACCESS_SELECTED ? implode(",", $validated["selected_case_numbers"]) : null;
            $access->save();
        }
        else
        {
            $cnt = OfficeUsersCaseAccess::where("office_user_id", $user->id)->where("customer_id", $validated["customer_id"])->count();
            
            if($cnt)
                throw new Exception(__("Istnieje już zdefiniowany dostęp dla tego klienta"));
            
            $access = new OfficeUsersCaseAccess;
            $access->office_user_id = $user->id;
            $access->customer_id = $validated["customer_id"];
            $access->type = $validated["type"];
            $access->case_numbers = $access->type == OfficeUsersCaseAccess::CASE_ACCESS_SELECTED ? implode(",", $validated["selected_case_numbers"]) : null;
            $access->save();
        }
        
        return ["success" => true];
    }
    
    public function deleteAccess(Request $request, $id, $aid)
    {
        OfficeUser::checkAccess("users:update");
        
        $user = OfficeUser::find($id);
        if(!$user)
            throw new Exception(__("Pracownik nie istnieje"));
        
        if($user->case_access_type != OfficeUser::CASE_ACCESS_SELECTED)
            throw new Exception(__("Brak zdefiniowanego dostęp do wybranych spraw"));
        
        $access = OfficeUsersCaseAccess::find($aid);
        if(!$access)
            throw new Exception(__("Roszczenie nie istnieje"));
        
        $access->delete();
        
        return ["success" => true];
    }
}
