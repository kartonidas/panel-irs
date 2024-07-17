<?php
 
namespace App\Observers;

use App\Models\CaseRegistry;

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
}
