<?php
 
namespace App\Http\Controllers\Office;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\View\View;

use App\Http\Requests\Office\CaseStoreRequest;
use App\Http\Requests\Office\CaseUpdateRequest;
use App\Libraries\Helper;
use App\Models\CaseRegistry;
use App\Models\Dictionary;
use App\Traits\Form;

class CaseRegisterController
{
    use Form;
    
    public function list(Request $request)
    {
        view()->share("activeMenuItem", "case_register");
        
        $filter = Helper::getFilter($request, "office:case_register");
        
        $cases = CaseRegistry::orderBy("id", "DESC");
        if(!empty($filter["customer_signature"]))
            $cases->where("customer_signature", "LIKE", "%" .$filter["customer_signature"] . "%");
        if(!empty($filter["rs_signature"]))
            $cases->where("rs_signature", "LIKE", "%" .$filter["rs_signature"] . "%");
            
        $cases = $cases->paginate(config("office.lists.size"));
        
        $vData = [
            "cases" => $cases,
            "filter" => $filter,
        ];
        
        return view("office.case-register.list", $vData);
    }
    
    public function caseCreate(Request $request)
    {
        view()->share("activeMenuItem", "case_register");
        
        $vData = [
            "caseStatuses" => Dictionary::getByType("case_status"),
            "form" => $request->old() ? $request->old() : [],
        ];
        return view("office.case-register.create", $vData);
    }
    
    public function caseCreatePost(CaseStoreRequest $request)
    {
        $validated = $request->validated();
        
        $case = new CaseRegistry;
        $case->customer_name = $validated["customer_name"];
        $case->customer_signature = $validated["customer_signature"];
        $case->rs_signature = $validated["rs_signature"];
        $case->opponent = $validated["opponent"];
        $case->status_id = $validated["status_id"];
        $case->death = $validated["death"] ?? 0;
        $case->date_of_death = $case->death ? $validated["date_of_death"] : null;
        $case->insolvency = $validated["insolvency"] ?? 0;
        $case->completed = $validated["completed"] ?? 0;
        $case->baliff = $validated["baliff"] ?? "";
        $case->court = $validated["court"] ?? "";
        $case->save();
        
        Helper::setMessage("office:cases", __("Sprawa została dodana"));
        if($this->isApply())
            return redirect()->route("office.case_register.update", $case->id);
        else
            return redirect()->route("office.case_register.show", $case->id);
    }
    
    public function caseUpdate(Request $request, $id)
    {
        view()->share("activeMenuItem", "case_register");
        
        $case = CaseRegistry::find($id);
        if(!$case)
            return redirect()->route("office.case_register")->withErrors(["msg" => __("Sprawa nie istnieje")]);
        
        $vData = [
            "case" => $case,
            "form" => $request->old() ? $request->old() : $case->toArray(),
            "caseStatuses" => Dictionary::getByType("case_status")
        ];
        return view("office.case-register.update", $vData);
    }
    
    public function caseUpdatePost(CaseUpdateRequest $request, $id)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            return redirect()->route("office.case_register")->withErrors(["msg" => __("Sprawa nie istnieje")]);
        
        $validated = $request->validated();
        
        Helper::setMessage("office:cases", __("Sprawa została zaktualizowana"));
        if($this->isApply())
            return redirect()->route("office.case_register.update", $case->id);
        else
            return redirect()->route("office.case_register.show", $case->id);
    }
    
    public function caseShow(Request $request, $id)
    {
        view()->share("activeMenuItem", "case_register");
        
        $case = CaseRegistry::find($id);
        if(!$case)
            return redirect()->route("office.case_register")->withErrors(["msg" => __("Sprawa nie istnieje")]);
        
        $vData = [
            "case" => $case,
        ];
        return view("office.case-register.show", $vData);
    }
}
