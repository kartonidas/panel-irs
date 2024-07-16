<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    protected $hidden = [
        "created_at", "updated_at"
    ];
    
    public static $sortable = ["name"];
    public static $defaultSortable = ["name", "asc"];
    public static $filter = ["name", "city"];
}
