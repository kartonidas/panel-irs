<?php
 
namespace App\Observers;

use App\Models\CaseRegistry;

use App\Models\CaseRegisterClaim;
use App\Models\CaseRegisterCourt;
use App\Models\CaseRegisterDocument;
use App\Models\CaseRegisterEnforcement;
use App\Models\CaseRegisterHistory;
use App\Models\CaseRegisterPayment;
use App\Models\CaseRegisterSchedule;

class CaseRegistryObserver
{
    public function creating(CaseRegistry $case)
    {
        $case->case_number = substr($case->rs_signature, 0, 7);
    }
    
    public function updating(CaseRegistry $case)
    {
        $case->case_number = substr($case->rs_signature, 0, 7);
    }
    
    public function deleted(CaseRegistry $case)
    {
        CaseRegisterClaim::where("case_registry_id", $case->id)->delete();
        CaseRegisterCourt::where("case_registry_id", $case->id)->delete();
        CaseRegisterEnforcement::where("case_registry_id", $case->id)->delete();
        CaseRegisterHistory::where("case_registry_id", $case->id)->delete();
        CaseRegisterPayment::where("case_registry_id", $case->id)->delete();
        CaseRegisterSchedule::where("case_registry_id", $case->id)->delete();
        
        $documents = CaseRegisterDocument::where("case_registry_id", $case->id)->get();
        foreach($documents as $document)
            $document->delete();
    }
}
