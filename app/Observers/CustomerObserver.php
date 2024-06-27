<?php
 
namespace App\Observers;

use App\Models\Customer;
use App\Models\CustomerCaseNumber;
use App\Models\CustomerSftp;
use App\Models\CustomerVisibilityField;
use App\Models\User;

class CustomerObserver
{
    public function deleted(Customer $customer)
    {
        CustomerCaseNumber::where("customer_id", $customer->id)->delete();
        User::where("customer_id", $customer->id)->delete();
        CustomerSftp::where("customer_id", $customer->id)->delete();
        CustomerVisibilityField::where("customer_id", $customer->id)->delete();
    }
}
