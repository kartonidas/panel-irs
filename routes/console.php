<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use App\Models\Export;
use App\Models\OfficeUser;
use App\Models\User;

Schedule::call(function () {
    OfficeUser::blockInactiveLongTimeAccount();
    User::blockInactiveLongTimeAccount();
    Export::clear();
})->hourly();