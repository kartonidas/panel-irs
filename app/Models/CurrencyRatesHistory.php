<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRatesHistory extends Model
{
    public $timestamps = false;
    protected $table = "currency_rates_history";
}
