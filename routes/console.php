<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use App\Libraries\NBPApi;
use App\Models\Export;
use App\Models\OfficeUser;
use App\Models\User;

Schedule::call(function () {
    OfficeUser::blockInactiveLongTimeAccount();
    User::blockInactiveLongTimeAccount();
    Export::clear();
})->hourly();

Schedule::call(function () {
    NBPApi::getCurrencies();
    NBPApi::getCurrencyRates();
})->dailyAt("12:30");