<?php

namespace App\Models;

use Throwable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Libraries\Ftp;
use App\Models\CaseRegistry;
use App\Models\Customer;
use App\Observers\CaseRegisterDocumentObserver;

#[ObservedBy([CaseRegisterDocumentObserver::class])]
class CaseRegisterDocument extends Model
{
    protected $hidden = [
        "created_at", "updated_at"
    ];
    
    public static $sortable = ["name", "date"];
    public static $defaultSortable = ["date", "desc"];
    public static $filter = ["date", "name"];
    
    public function deleteFile()
    {
        $case = CaseRegistry::find($this->case_registry_id);
        if($case)
        {
            $customer = $case->getCustomer();
            if($customer)
            {
                try
                {
                    $ftp = FTP::getFTPDriver($customer);
                    if($ftp)
                        $ftp->delete($this->file);
                }
                catch(Throwable $e) {}
            }
        }
    }
}
