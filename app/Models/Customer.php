<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\User;
use App\Models\CustomerCaseNumber;
use App\Models\CustomerSftp;
use App\Observers\CustomerObserver;

#[ObservedBy([CustomerObserver::class])]
class Customer extends Model
{
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    public function caseNumbers(): HasMany
    {
        return $this->hasMany(CustomerCaseNumber::class);
    }
    
    public function sftp(): HasOne
    {
        return $this->hasOne(CustomerSftp::class);
    }
    
    public function scopeSearchName(Builder $query, string $name)
    {
        $name = preg_replace("/\s+/", " ", $name);
        $name = explode(" ", $name);
        $name = array_map("trim", array_filter($name));
        if(!empty($name))
        {
            $query->where(function($q) use($name) {
                foreach($name as $n)
                    $q->where("name", "REGEXP", $n);
            });
        }
    }
    
    public function getAssignedCaseNumbers()
    {
        $numbers = [];
        foreach($this->caseNumbers()->get() as $caseNumberRow)
            $numbers[] = $caseNumberRow->number;
        return $numbers;
    }
    
    public function assignCaseNumbers($numbers)
    {
        $currentAssignedNumbers = $this->getAssignedCaseNumbers();
        
        $newNumbers = array_diff($numbers, $currentAssignedNumbers);
        if(!empty($newNumbers))
        {
            foreach($newNumbers as $number)
            {
                $row = new CustomerCaseNumber;
                $row->customer_id = $this->id;
                $row->number = $number;
                $row->save();
            }
        }
        
        $deleteNumbers = array_diff($currentAssignedNumbers, $numbers);
        if(!empty($deleteNumbers))
            CustomerCaseNumber::where("customer_id", $this->id)->whereIn("number", $deleteNumbers)->delete();
    }
    
    public function ensureSftpConfigRow()
    {
        $config = CustomerSftp::where("customer_id", $this->id)->first();
        if(!$config)
        {
            $config = new CustomerSftp;
            $config->customer_id = $this->id;
            $config->save();
        }
        return $config;
    }
}