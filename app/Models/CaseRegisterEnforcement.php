<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Dictionary;

class CaseRegisterEnforcement extends Model
{
    protected $hidden = [
        "created_at", "updated_at"
    ];
    
    public static $sortable = ["date", "execution_status_id", "baliff", "signature"];
    public static $defaultSortable = ["date", "desc"];
    public static $filter = ["signature", "execution_status_id", "date_from", "date_to"];
    
    public function getExecutionStatusName()
    {
        $statuses = Dictionary::getByType("case_execution_status");
        return $statuses[$this->execution_status_id] ?? $this->execution_status_id;
    }
}
