<?php
 
namespace App\Http\Controllers\Office\Ajax;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\View\View;

use App\Abstracts\Office\AjaxExportControllerAbstract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Office\CasePaymentRequest;
use App\Libraries\Helper;
use App\Models\CaseRegistry;
use App\Models\CaseRegisterPayment;
use App\Models\OfficeUser;
use App\Traits\AjaxTable;

class CaseRegisterPaymentController extends AjaxExportControllerAbstract
{
    use AjaxTable;
    
    protected function modelName() : string
    {
        return CaseRegisterPayment::class;
    }
    
    public function exportBaseName() : string
    {
        return __("finanse");
    }
    
    public function list(Request $request, $id, $export = false)
    {
        $params = $this->getAjaxTableParams($request);
        $sort = $this->getSortOrder($request, false);
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $payments = $case->payments();
        
        if(!empty($params["filter"]["date_from"]))
            $payments->where("date", ">=", $params["filter"]["date_from"]);
        if(!empty($params["filter"]["date_to"]))
            $payments->where("date", "<=", $params["filter"]["date_to"]);
        
        $payments->orderBy($sort[0], $sort[1]);
        $maxRows = $payments->count();

        $payments = !$export ? $payments->paginate($params["topRecords"]) : $payments->get();

        $vData = [
            "case" => $case,
            "payments" => $payments,
        ];

        if($export)
            return view("office.case-register.export.payment-table", $vData);
        
        $view = view("office.case-register.table.payment-table", $vData);
        
        $out = [
            "table" => $view->render(),
            "maxrows" => $maxRows,
            "paginator" => $payments->render("office.partials.pagination")->toHtml()
        ];
        
		return $out;
    }
        
    public function getPayment(Request $request, $id, $pid)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $payment = CaseRegisterPayment::find($pid);
        if(!$payment)
            throw new Exception(__("Wpłata nie istnieje"));
        
        return [
            "data" => $payment
        ];
    }
    
    public function PaymentPost(CasePaymentRequest $request, $id)
    {
        $validated = $request->validated();
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        if(!empty($validated["id"]))
        {
            $payment = CaseRegisterPayment::find($validated["id"]);
            if(!$payment)
                throw new Exception(__("Wpłata nie istnieje"));
            
            $payment->date = $validated["date"];
            $payment->amount = $validated["amount"];
            $payment->currency = $validated["currency"] ?? "PLN";
            $payment->save();
        }
        else
        {
            $payment = new CaseRegisterPayment;
            $payment->case_registry_id = $case->id;
            $payment->date = $validated["date"];
            $payment->amount = $validated["amount"];
            $payment->currency = $validated["currency"] ?? "PLN";
            $payment->save();
        }
        
        return ["success" => true];
    }
    
    public function deletePayment(Request $request, $id, $pid)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        OfficeUser::checkCaseAccess($case);
        
        $payment = CaseRegisterPayment::find($pid);
        if(!$payment)
            throw new Exception(__("Wpłata nie istnieje"));
        
        $payment->delete();
        
        return ["success" => true];
    }
}