<?php
 
namespace App\Observers;

use App\Models\CaseRegistry;
use App\Models\CaseRegisterPayment;
use App\Models\Currency;

class CaseRegisterPaymentObserver
{
    public function creating(CaseRegisterPayment $payment)
    {
        $payment->amount_pln = Currency::exchange($payment->amount, $payment->currency);
    }
    
    public function created(CaseRegisterPayment $payment)
    {
        $case = CaseRegistry::find($payment->case_registry_id);
        $case?->calculateBalance();
    }
    
    public function updating(CaseRegisterPayment $payment)
    {
        $payment->amount_pln = Currency::exchange($payment->amount, $payment->currency);
    }
    
    public function updated(CaseRegisterPayment $payment)
    {
        $case = CaseRegistry::find($payment->case_registry_id);
        $case?->calculateBalance();
    }
    
    public function deleted(CaseRegisterPayment $payment)
    {
        $case = CaseRegistry::find($payment->case_registry_id);
        $case?->calculateBalance();
    }
}