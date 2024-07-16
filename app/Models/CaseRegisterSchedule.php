<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseRegisterSchedule extends Model
{
    protected $hidden = [
        "created_at", "updated_at"
    ];
    
    public static $sortable = ["date", "amount"];
    public static $defaultSortable = ["date", "desc"];
    public static $filter = ["date_from", "date_to"];
}
