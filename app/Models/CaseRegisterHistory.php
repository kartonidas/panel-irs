<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Dictionary;

class CaseRegisterHistory extends Model
{
    protected $hidden = [
        "created_at", "updated_at"
    ];
    
    public static $sortable = ["date", "history_action_id"];
    public static $defaultSortable = ["date", "desc"];
    public static $filter = ["action", "date_from", "date_to"];
    
    public function getActionName()
    {
        $statuses = Dictionary::getByType("case_history_action");
        return $statuses[$this->history_action_id] ?? $this->history_action_id;
    }
}
