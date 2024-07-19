<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use App\Observers\CaseRegisterClaimObserver;

#[ObservedBy([CaseRegisterClaimObserver::class])]
class CaseRegisterClaim extends Model
{
    protected $hidden = [
        "created_at", "updated_at"
    ];
    
    public static $sortable = ["amount", "date", "due_date", "mark", "amount_pln"];
    public static $defaultSortable = ["due_date", "desc"];
    public static $filter = ["date", "due_date", "mark"];
}
