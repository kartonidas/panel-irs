<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

use App\Models\Dictionary;
use App\Models\CaseRegisterClaim;
use App\Models\CaseRegisterCourt;
use App\Models\CaseRegisterDocument;
use App\Models\CaseRegisterEnforcement;
use App\Models\CaseRegisterHistory;
use App\Models\CaseRegisterPayment;
use App\Models\CaseRegisterSchedule;
use App\Models\Court;
use App\Models\Customer;
use App\Models\OfficeUser;
use App\Observers\CaseRegistryObserver;

#[ObservedBy([CaseRegistryObserver::class])]
class CaseRegistry extends Model
{
    public static $sortable = ["customer_signature", "rs_signature", "opponent", "customer_name", "opponent_pesel", "opponent_regon", "opponent_nip", "opponent_krs", "opponent_phone", "opponent_email", "balance"];
    public static $defaultSortable = ["customer_signature", "asc"];
    public static $filter = ["customer_signature", "rs_signature", "customer_id", "status_id"];
    
    public function getStatusName()
    {
        $statuses = Dictionary::getByType("case_status");
        return $statuses[$this->status_id] ?? $this->status_id;
    }
    
    private static $customers = [];
    public function getCustomerName()
    {
        if(empty(static::$customers))
        {
            $customers = Customer::all();
            foreach($customers as $customer)
                static::$customers[$customer->id] = $customer->toArray();
        }
        
        return static::$customers[$this->customer_id]["name"] ?? $this->customer_id;
    }
    
    public function getCustomer()
    {
        return Customer::find($this->customer_id);
    }
    
    public function hasCustomerSftpConfigured() : bool
    {
        $customer = $this->getCustomer();
        
        if($customer && $customer->hasCustomerSftpConfigured())
            return true;
        
        return false;
    }
    
    private static $courts = [];
    public function getCourtName()
    {
        if(empty(static::$courts))
        {
            $courts = Court::all();
            foreach($courts as $court)
                static::$courts[$court->id] = $court->toArray();
        }
        
        return static::$courts[$this->court_id]["name"] ?? $this->court_id;
    }
    
    public function claims(): HasMany
    {
        return $this->hasMany(CaseRegisterClaim::class);
    }
    
    public function histories(): HasMany
    {
        return $this->hasMany(CaseRegisterHistory::class);
    }
    
    public function schedules(): HasMany
    {
        return $this->hasMany(CaseRegisterSchedule::class);
    }
    
    public function courts(): HasMany
    {
        return $this->hasMany(CaseRegisterCourt::class);
    }
    
    public function enforcements() : HasMany
    {
        return $this->hasMany(CaseRegisterEnforcement::class);
    }
    
    public function payments() : HasMany
    {
        return $this->hasMany(CaseRegisterPayment::class);
    }
    
    public function documents() : HasMany
    {
        return $this->hasMany(CaseRegisterDocument::class);
    }
    
    public function scopeByUser(Builder $query): void
    {
        $user = Auth::guard("office")->user();
        if($user->case_access_type != OfficeUser::CASE_ACCESS_ALL)
        {
            $customerFullAccess = [];
            $specifiedCaseNumbersAccess = [];
            $caseAccess = $user->caseAccess()->get();
            if($caseAccess->isEmpty())
            {
                $query->whereRaw("1=2");
            }
            else
            {
                foreach($caseAccess as $access)
                {
                    if($access->type == OfficeUsersCaseAccess::CASE_ACCESS_ALL)
                        $customerFullAccess[] = $access->customer_id;
                    else
                        $specifiedCaseNumbersAccess[$access->customer_id] = $access->getSelectedCaseNumbers();
                }
                
                $query->where(function($q) use($customerFullAccess, $specifiedCaseNumbersAccess) {
                    if(!empty($customerFullAccess))
                        $q->whereIn("customer_id", $customerFullAccess);
                        
                    if(!empty($specifiedCaseNumbersAccess))
                    {
                        foreach($specifiedCaseNumbersAccess as $customerId => $caseNumbers)
                        {
                            $q->orWhere(function($q) use($customerId, $caseNumbers) {
                                $q->where("customer_id", $customerId)->whereIn("case_number", $caseNumbers);
                            });
                        }
                    }
                });
            }
        }
    }
    
    public function calculateBalance()
    {
        $claims = $this->claims()->sum("amount_pln");
        $payments = $this->payments()->sum("amount_pln");
        
        $this->balance = $claims - $payments;
        $this->saveQuietly();
    }
}