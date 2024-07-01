<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Dictionary;

class CaseRegistry extends Model
{
    public static $sortable = ["customer_signature", "rs_signature", "opponent"];
    public static $defaultSortable = ["customer_signature", "asc"];
    public static $filter = ["customer_signature", "rs_signature"];
    
    public function getStatusName()
    {
        $statuses = Dictionary::getByType("case_status");
        return $statuses[$this->status_id] ?? $this->status_id;
    }
}