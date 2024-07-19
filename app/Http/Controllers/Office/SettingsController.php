<?php

namespace App\Http\Controllers\Office;

use Exception;
use Illuminate\Http\Request;

use App\Libraries\Helper;
use App\Libraries\NBPApi;
use App\Models\Currency;
use App\Models\OfficeUser;

class SettingsController
{
    public function index(Request $request)
    {
        OfficeUser::checkAccess("settings:update");
        view()->share("activeMenuItem", "settings");
        
        $currenciesArray = [];
        $currencies = Currency::orderBy("nbp_table", "ASC")->orderBy("symbol", "ASC")->get();
        if(!$currencies->isEmpty())
        {
            foreach($currencies as $currency)
                $currenciesArray[$currency->nbp_table][] = $currency;
        }
        
        $vData = [
            "currenciesTable" => $currenciesArray,
            "activeTab" => $request->session()->get("settings_tab", "currencies"),
        ];
        
        return view("office.settings.index", $vData);
    }
    
    public function currenciesSave(Request $request)
    {
        OfficeUser::checkAccess("settings:update");
        $request->session()->flash("settings_tab", "currencies");
        
        $checkedCurrencies = [""];
        $currencies = $request->input("currency", []);
        foreach($currencies as $currency)
            $checkedCurrencies[] = $currency;

        Currency::whereIn("symbol", $checkedCurrencies)->update(["active" => 1]);
        Currency::whereNotIn("symbol", $checkedCurrencies)->update(["active" => 0]);
        NBPApi::getCurrencyRates();
        
        Helper::setMessage("office:settings", "Ustawienia zostały zapisane");
        return redirect()->back();
    }
}
