<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\CurrencyRates;

class Currency extends Model
{
    public $timestamps = false;
    protected $table = "currency";

    public static function getAllowedCurrencies()
    {
        $out = ["PLN"];

        $rows = self::where("active", 1)->orderBy("symbol", "ASC")->get();
        if(!$rows->isEmpty())
        {
            foreach($rows as $row)
                $out[] = $row->symbol;
        }

        return $out;
    }
    
    public static function exchange($value, $currency)
    {
        if($currency == "PLN")
            return $value;
        
        $rate = CurrencyRates::select("rate")->where("symbol", $currency)->first();
        if($rate)
        {
            $rate = $rate->rate;
            
            return $value * $rate;
        }
        
        return $value;
    }
}
