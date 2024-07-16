<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseRegisterClaim extends Model
{
    protected $hidden = [
        "created_at", "updated_at"
    ];
    
    public static $sortable = ["amount", "date", "due_date", "mark"];
    public static $defaultSortable = ["due_date", "desc"];
    public static $filter = ["date", "due_date", "mark"];
}
