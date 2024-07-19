<?php
 
namespace App\Observers;

use App\Models\CaseRegistry;
use App\Models\CaseRegisterClaim;
use App\Models\Currency;

class CaseRegisterClaimObserver
{
    public function creating(CaseRegisterClaim $claim)
    {
        $claim->amount_pln = Currency::exchange($claim->amount, $claim->currency);
    }
    
    public function created(CaseRegisterClaim $claim)
    {
        $case = CaseRegistry::find($claim->case_registry_id);
        $case?->calculateBalance();
    }
    
    public function updating(CaseRegisterClaim $claim)
    {
        $claim->amount_pln = Currency::exchange($claim->amount, $claim->currency);
    }
    
    public function updated(CaseRegisterClaim $claim)
    {
        $case = CaseRegistry::find($claim->case_registry_id);
        $case?->calculateBalance();
    }
    
    public function deleted(CaseRegisterClaim $claim)
    {
        $case = CaseRegistry::find($claim->case_registry_id);
        $case?->calculateBalance();
    }
}