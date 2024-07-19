<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use App\Observers\CaseRegisterPaymentObserver;

#[ObservedBy([CaseRegisterPaymentObserver::class])]
class CaseRegisterPayment extends Model
{
    protected $hidden = [
        "created_at", "updated_at"
    ];
    
    public static $sortable = ["date", "amount", "amount_pln"];
    public static $defaultSortable = ["date", "desc"];
    public static $filter = ["date_from", "date_to"];
}
