<?php
 
namespace App\Http\Controllers\Office;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\View\View;

use App\Http\Controllers\Controller;
use App\Http\Requests\Office\CaseClaimRequest;
use App\Http\Requests\Office\CaseStoreRequest;
use App\Http\Requests\Office\CaseUpdateRequest;
use App\Libraries\Helper;
use App\Models\CaseRegistry;
use App\Models\CaseRegisterClaim;
use App\Models\CaseRegisterCourt;
use App\Models\CaseRegisterDocument;
use App\Models\CaseRegisterEnforcement;
use App\Models\CaseRegisterHistory;
use App\Models\CaseRegisterPayment;
use App\Models\CaseRegisterSchedule;
use App\Models\Court;
use App\Models\Customer;
use App\Models\Dictionary;
use App\Traits\Form;

class CaseRegisterController extends Controller
{
    use Form;
    
    protected function modelName() : string
    {
        return CaseRegistry::class;
    }
    
    public function list(Request $request)
    {
        view()->share("activeMenuItem", "case_register");
        
        $filter = $this->getFilter($request);
        $sort = $this->getSortOrder($request);
        
        $cases = CaseRegistry::select("case_registries.*");
        
        switch($sort[0])
        {
            case "customer_name":
                $cases->leftJoin("customers", "customers.id", "=", "case_registries.customer_id");
                $cases->addSelect("customers.name AS customer_name");
            break;
        }
        
        if(!empty($filter["customer_signature"]))
            $cases->where("customer_signature", "LIKE", "%" . $filter["customer_signature"] . "%");
        if(!empty($filter["customer_id"]))
            $cases->where("customer_id", $filter["customer_id"]);
        if(!empty($filter["rs_signature"]))
            $cases->where("rs_signature", "LIKE", "%" . $filter["rs_signature"] . "%");
        if(!empty($filter["status_id"]))
            $cases->where("status_id", $filter["status_id"]);
            
        $cases->orderBy($sort[0], $sort[1]);
        $cases = $cases->paginate($this->getPageSize($request));
        
        $vData = [
            "cases" => $cases,
            "filter" => $filter,
            "sort" => $sort,
            "sortColumns" => $this->getSortableFields($sort),
            "size" => $this->getPageSize($request),
            "customers" => Customer::orderBy("name", "ASC")->get(),
            "caseStatuses" => Dictionary::getByType("case_status"),
        ];
        
        return view("office.case-register.list", $vData);
    }
    
    public function caseCreate(Request $request)
    {
        view()->share("activeMenuItem", "case_register");
        
        $vData = [
            "caseStatuses" => Dictionary::getByType("case_status"),
            "form" => $request->old() ? $request->old() : [],
            "customers" => Customer::orderBy("name", "ASC")->get(),
            "courts" => Court::orderBy("name", "ASC")->get(),
        ];
        return view("office.case-register.create", $vData);
    }
    
    public function caseCreatePost(CaseStoreRequest $request)
    {
        $validated = $request->validated();
        
        $case = new CaseRegistry;
        $case->customer_id = $validated["customer_id"];
        $case->customer_signature = $validated["customer_signature"];
        $case->rs_signature = $validated["rs_signature"];
        $case->opponent = $validated["opponent"];
        $case->opponent_pesel = $validated["opponent_pesel"] ?? null;
        $case->opponent_regon = $validated["opponent_regon"] ?? null;
        $case->opponent_nip = $validated["opponent_nip"] ?? null;
        $case->opponent_krs = $validated["opponent_krs"] ?? null;
        $case->opponent_street = $validated["opponent_street"] ?? null;
        $case->opponent_zip = $validated["opponent_zip"] ?? null;
        $case->opponent_city = $validated["opponent_city"] ?? null;
        $case->opponent_phone = $validated["opponent_phone"] ?? null;
        $case->opponent_email = $validated["opponent_email"] ?? null;
        $case->status_id = $validated["status_id"];
        $case->death = $validated["death"] ?? 0;
        $case->date_of_death = $case->death ? $validated["date_of_death"] : null;
        $case->insolvency = $validated["insolvency"] ?? 0;
        $case->completed = $validated["completed"] ?? 0;
        $case->baliff = $validated["baliff"] ?? "";
        $case->court_id = $validated["court_id"] ?? "";
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
            "caseStatuses" => Dictionary::getByType("case_status"),
            "customers" => Customer::orderBy("name", "ASC")->get(),
            "courts" => Court::orderBy("name", "ASC")->get(),
        ];
        return view("office.case-register.update", $vData);
    }
    
    public function caseUpdatePost(CaseUpdateRequest $request, $id)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            return redirect()->route("office.case_register")->withErrors(["msg" => __("Sprawa nie istnieje")]);
        
        $validated = $request->validated();
        
        $case->customer_id = $validated["customer_id"];
        $case->customer_signature = $validated["customer_signature"];
        $case->rs_signature = $validated["rs_signature"];
        $case->opponent = $validated["opponent"];
        $case->opponent_pesel = $validated["opponent_pesel"] ?? null;
        $case->opponent_regon = $validated["opponent_regon"] ?? null;
        $case->opponent_nip = $validated["opponent_nip"] ?? null;
        $case->opponent_krs = $validated["opponent_krs"] ?? null;
        $case->opponent_street = $validated["opponent_street"] ?? null;
        $case->opponent_zip = $validated["opponent_zip"] ?? null;
        $case->opponent_city = $validated["opponent_city"] ?? null;
        $case->opponent_phone = $validated["opponent_phone"] ?? null;
        $case->opponent_email = $validated["opponent_email"] ?? null;
        $case->status_id = $validated["status_id"];
        $case->death = $validated["death"] ?? 0;
        $case->date_of_death = $case->death ? $validated["date_of_death"] : null;
        $case->insolvency = $validated["insolvency"] ?? 0;
        $case->completed = $validated["completed"] ?? 0;
        $case->baliff = $validated["baliff"] ?? "";
        $case->court_id = $validated["court_id"] ?? "";
        $case->save();
        
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
            "claims" => $case->claims()->orderBy(CaseRegisterClaim::$defaultSortable[0], CaseRegisterClaim::$defaultSortable[1])->paginate(config("office.lists.ajax.size")),
            "histories" => $case->histories()->orderBy(CaseRegisterHistory::$defaultSortable[0], CaseRegisterHistory::$defaultSortable[1])->paginate(config("office.lists.ajax.size")),
            "schedules" => $case->schedules()->orderBy(CaseRegisterSchedule::$defaultSortable[0], CaseRegisterSchedule::$defaultSortable[1])->paginate(config("office.lists.ajax.size")),
            "courts" => $case->courts()->orderBy(CaseRegisterCourt::$defaultSortable[0], CaseRegisterCourt::$defaultSortable[1])->paginate(config("office.lists.ajax.size")),
            "enforcements" => $case->enforcements()->orderBy(CaseRegisterEnforcement::$defaultSortable[0], CaseRegisterEnforcement::$defaultSortable[1])->paginate(config("office.lists.ajax.size")),
            "payments" => $case->payments()->orderBy(CaseRegisterPayment::$defaultSortable[0], CaseRegisterPayment::$defaultSortable[1])->paginate(config("office.lists.ajax.size")),
            "documents" => $case->documents()->orderBy(CaseRegisterDocument::$defaultSortable[0], CaseRegisterDocument::$defaultSortable[1])->paginate(config("office.lists.ajax.size")),
            "hasSftp" => $case->hasCustomerSftpConfigured(),
            "dictionaries" => [
                "historyActions" => Dictionary::getByType("case_history_action"),
                "courts" => Court::orderBy("name", "ASC")->get(),
                "caseStatuses" => Dictionary::getByType("case_status"),
                "caseModes" => Dictionary::getByType("case_mode"),
                "caseExecutionStatuses" => Dictionary::getByType("case_execution_status"),
            ]
        ];
        return view("office.case-register.show", $vData);
    }
}
