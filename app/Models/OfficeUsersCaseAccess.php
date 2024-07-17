<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Customer;

class OfficeUsersCaseAccess extends Model
{
    protected $hidden = [
        "created_at", "updated_at"
    ];
    
    public static $sortable = ["created_at"];
    public static $defaultSortable = ["created_at", "desc"];
    
    public const CASE_ACCESS_ALL = "all";
    public const CASE_ACCESS_SELECTED = "selected";
    
    public static function getCaseAccessTypes() : array
    {
        return [
            self::CASE_ACCESS_ALL => __("Wszystkie sprawy"),
            self::CASE_ACCESS_SELECTED => __("Wybrane sprawy"),
        ];
    }
    
    public function getTypeName()
    {
        return self::getCaseAccessTypes()[$this->type] ?? $this->type;
    }
    
    public function getCustomerName()
    {
        $customer = Customer::find($this->customer_id);
        return $customer ? $customer->name : $this->customer_id;
    }
    
    public function getSelectedCaseNumbers()
    {
        return $this->type == self::CASE_ACCESS_SELECTED ? explode(",", $this->case_numbers) : [];
    }
}
