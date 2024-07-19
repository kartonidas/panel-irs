<?php

namespace App\Libraries;

use App\Models\Currency;
use App\Models\CurrencyRates;
use App\Models\CurrencyRatesHistory;
use Illuminate\Support\Facades\Http;

class NBPApi
{
    private static $endpoint = "http://api.nbp.pl/api";
    public static function getCurrencies()
    {
        foreach(["A", "B"] as $table)
        {
            $response = Http::get(static::$endpoint . "/exchangerates/tables/" . $table);
            $data = $response->json();
            if($data && !empty($data[0]["rates"]))
            {
                foreach($data[0]["rates"] as $rate)
                {
                    $currency = Currency::where("symbol", $rate["code"])->first();
                    if(!$currency)
                    {
                        $currency = new Currency;
                        $currency->symbol = $rate["code"];
                        $currency->name = $rate["currency"];
                        $currency->active = 0;
                        $currency->nbp_table = $table;
                        $currency->save();
                    }
                    else
                    {
                        $currency->nbp_table = $table;
                        $currency->save();
                    }
                }
            }
        }
    }

    public static function getCurrencyRates()
    {
        $currencies = Currency::where("active", 1)->get();
        if(!$currencies->isEmpty())
        {
            foreach($currencies as $currency)
            {
                $response = Http::get(static::$endpoint . "/exchangerates/rates/" . $currency->nbp_table . "/" . $currency->symbol);
                if($response->ok())
                {
                    $data = $response->json();
                    if(!empty($data["rates"][0]))
                    {
                        $rate = CurrencyRates::where("symbol", $data["code"])->first();
                        if(!$rate)
                        {
                            $rate = new CurrencyRates;
                            $rate->symbol = $data["code"];
                        }
                        $rate->rate = $data["rates"][0]["mid"];
                        $rate->save();

                        $historyRate = CurrencyRatesHistory::where("symbol", $data["code"])->where("effective_date", $data["rates"][0]["effectiveDate"])->first();
                        if(!$historyRate)
                        {
                            $historyRate = new CurrencyRatesHistory;
                            $historyRate->symbol = $data["code"];
                            $historyRate->effective_date = $data["rates"][0]["effectiveDate"];
                            $historyRate->rate = $data["rates"][0]["mid"];
                            $historyRate->save();
                        }
                    }
                }
            }
        }
    }
}