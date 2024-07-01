<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Office;
use App\Http\Controllers\Panel;
use App\Http\Middleware\AuthOffice;
use App\Http\Middleware\AuthOfficeActivityTime;
use App\Http\Middleware\AuthPanel;

Route::middleware([AuthPanel::class])->group(function () {
    Route::get("/", [Panel\IndexController::class, "dashboard"])->name("panel");
    Route::get("/wyloguj", [Panel\LoginController::class, "logout"])->name("logout");
    
    Route::get('/profile', [Panel\UserController::class, "profile"])->name("profile");
    Route::post('/profile', [Panel\UserController::class, "profilePost"])->name("profile.post");
});
Route::get("/logowanie", [Panel\LoginController::class, "login"])->name("login");
Route::post("/logowanie", [Panel\LoginController::class, "loginPost"])->name("login.post");


Route::prefix(env("ADMIN_PANEL_PREFIX"))->group(function () {
    Route::post("/check-activity", [Office\LoginController::class, "checkActivity"])->name("office.check-activity");
    
    Route::middleware([AuthOffice::class, AuthOfficeActivityTime::class])->group(function () {
        Route::get("/", [Office\IndexController::class, "dashboard"])->name("office");
        Route::get("/wyloguj", [Office\LoginController::class, "logout"])->name("office.logout");
        
        Route::get('/profile', [Office\OfficeUserController::class, "profile"])->name("office.profile");
        Route::post('/profile', [Office\OfficeUserController::class, "profilePost"])->name("office.profile.post");
        
        Route::get('/users', [Office\OfficeUserController::class, "list"])->name("office.users");
        Route::get('/users/sort', [Office\OfficeUserController::class, "sort"])->name("office.users.sort");
        Route::get('/users/filter', [Office\OfficeUserController::class, "filter"])->name("office.users.filter");
        Route::get('/users/filter/clear', [Office\OfficeUserController::class, "clearFilter"])->name("office.users.filter.clear");
        Route::get('/user/new', [Office\OfficeUserController::class, "userCreate"])->name("office.user.create");
        Route::post('/user/new', [Office\OfficeUserController::class, "userCreateSave"])->name("office.user.create.post");
        Route::get('/user/edit/{id}', [Office\OfficeUserController::class, "userUpdate"])->name("office.user.update")->where("id", "[0-9]+");
        Route::post('/user/edit/{id}', [Office\OfficeUserController::class, "userUpdateSave"])->name("office.user.update.post")->where("id", "[0-9]+");
        Route::post('/user/delete/{id}', [Office\OfficeUserController::class, "userDelete"])->name("office.user.delete")->where("id", "[0-9]+");
        Route::post('/user/block/{id}', [Office\OfficeUserController::class, "userBlockAccount"])->name("office.user.block")->where("id", "[0-9]+");
        Route::post('/user/unblock/{id}', [Office\OfficeUserController::class, "userUnblockAccount"])->name("office.user.unblock")->where("id", "[0-9]+");
        
        Route::get('/permissions', [Office\OfficePermissionsController::class, "list"])->name("office.permissions");
        Route::get('/permissions/sort', [Office\OfficePermissionsController::class, "sort"])->name("office.permissions.sort");
        Route::get('/permission/new', [Office\OfficePermissionsController::class, "create"])->name("office.permission.create");
        Route::post('/permission/new', [Office\OfficePermissionsController::class, "createPost"])->name("office.permission.create.post");
        Route::get('/permission/edit/{id}', [Office\OfficePermissionsController::class, "update"])->name("office.permission.update")->where("id", "[0-9]+");
        Route::post('/permission/edit/{id}', [Office\OfficePermissionsController::class, "updatePost"])->name("office.permission.update.post")->where("id", "[0-9]+");
        Route::post('/permission/delete/{id}', [Office\OfficePermissionsController::class, "delete"])->name("office.permission.delete")->where("id", "[0-9]+");

        Route::get('/customers', [Office\CustomerController::class, "list"])->name("office.customers");
        Route::get('/customers/sort', [Office\CustomerController::class, "sort"])->name("office.customers.sort");
        Route::get('/customers/filter', [Office\CustomerController::class, "filter"])->name("office.customers.filter");
        Route::get('/customers/filter/clear', [Office\CustomerController::class, "clearFilter"])->name("office.customers.filter.clear");
        Route::get('/customer/new', [Office\CustomerController::class, "customerCreate"])->name("office.customer.create");
        Route::post('/customer/new', [Office\CustomerController::class, "customerCreatePost"])->name("office.customer.create.post");
        Route::get('/customer/show/{id}', [Office\CustomerController::class, "customerShow"])->name("office.customer.show")->where("id", "[0-9]+");
        Route::get('/customer/edit/{id}', [Office\CustomerController::class, "customerUpdate"])->name("office.customer.update")->where("id", "[0-9]+");
        Route::post('/customer/edit/{id}', [Office\CustomerController::class, "customerUpdatePost"])->name("office.customer.update.post")->where("id", "[0-9]+");
        Route::post('/customer/delete/{id}', [Office\CustomerController::class, "customerDelete"])->name("office.customer.delete")->where("id", "[0-9]+");
        Route::get('/customer/sftp/{id}', [Office\CustomerController::class, "customerSftp"])->name("office.customer.sftp")->where("id", "[0-9]+");
        Route::post('/customer/sftp/{id}', [Office\CustomerController::class, "customerSftpPost"])->name("office.customer.sftp.post")->where("id", "[0-9]+");
        Route::get('/customer/{id}/user/new', [Office\CustomerController::class, "customerUserCreate"])->name("office.customer.user.create")->where("id", "[0-9]+");
        Route::post('/customer/{id}/user/new', [Office\CustomerController::class, "customerUserCreatePost"])->name("office.customer.user.create.post")->where("id", "[0-9]+");
        Route::get('/customer/{id}/user/edit/{uid}', [Office\CustomerController::class, "customerUserUpdate"])->name("office.customer.user.update")->where("id", "[0-9]+")->where("uid", "[0-9]+");
        Route::post('/customer/{id}/user/edit/{uid}', [Office\CustomerController::class, "customerUserUpdatePost"])->name("office.customer.user.update.post")->where("id", "[0-9]+")->where("uid", "[0-9]+");
        Route::post('/customer/{id}/user/delete/{uid}', [Office\CustomerController::class, "customerUserDelete"])->name("office.customer.user.delete")->where("id", "[0-9]+")->where("uid", "[0-9]+");
        Route::post('/customer/{id}/user/block/{uid}', [Office\CustomerController::class, "customerUserBlockAccount"])->name("office.customer.user.block")->where("id", "[0-9]+")->where("uid", "[0-9]+");
        Route::post('/customer/{id}/user/unblock/{uid}', [Office\CustomerController::class, "customerUserUnblockAccount"])->name("office.customer.user.unblock")->where("id", "[0-9]+")->where("uid", "[0-9]+");
        Route::get('/customer/{id}/visibility-elements', [Office\CustomerController::class, "customerVisibilityElements"])->name("office.customer.visibility-elements")->where("id", "[0-9]+");
        Route::post('/customer/{id}/visibility-elements', [Office\CustomerController::class, "customerVisibilityElementsPost"])->name("office.customer.visibility-elements.post")->where("id", "[0-9]+");
        
        Route::get('/dictionaries', [Office\DictionaryController::class, "list"])->name("office.dictionaries");
        Route::get('/dictionaries/filter', [Office\DictionaryController::class, "filter"])->name("office.dictionaries.filter");
        Route::get('/dictionaries/filter/clear', [Office\DictionaryController::class, "clearFilter"])->name("office.dictionaries.filter.clear");
        Route::get('/dictionary/new', [Office\DictionaryController::class, "dictionaryCreate"])->name("office.dictionary.create");
        Route::post('/dictionary/new', [Office\DictionaryController::class, "dictionaryCreatePost"])->name("office.dictionary.create.post");
        Route::get('/dictionary/edit/{id}', [Office\DictionaryController::class, "dictionaryUpdate"])->name("office.dictionary.update")->where("id", "[0-9]+");
        Route::post('/dictionary/edit/{id}', [Office\DictionaryController::class, "dictionaryUpdatePost"])->name("office.dictionary.update.post")->where("id", "[0-9]+");
        Route::post('/dictionary/delete/{id}', [Office\DictionaryController::class, "dictionaryDelete"])->name("office.dictionary.delete")->where("id", "[0-9]+");
        
        Route::get('/case-register', [Office\CaseRegisterController::class, "list"])->name("office.case_register");
        Route::get('/case-register/sort', [Office\CaseRegisterController::class, "sort"])->name("office.case_register.sort");
        Route::get('/case-register/filter', [Office\CaseRegisterController::class, "filter"])->name("office.case_register.filter");
        Route::get('/case-register/filter/clear', [Office\CaseRegisterController::class, "clearFilter"])->name("office.case_register.filter.clear");
        Route::get('/case-register/page-size/{size}', [Office\CaseRegisterController::class, "setPageSize"])->name("office.case_register.size")->where("size", "[0-9]+");
        Route::get('/case-register/new', [Office\CaseRegisterController::class, "caseCreate"])->name("office.case_register.create");
        Route::post('/case-register/new', [Office\CaseRegisterController::class, "caseCreatePost"])->name("office.case_register.create.post");
        Route::get('/case-register/show/{id}', [Office\CaseRegisterController::class, "caseShow"])->name("office.case_register.show")->where("id", "[0-9]+");
        Route::get('/case-register/edit/{id}', [Office\CaseRegisterController::class, "caseUpdate"])->name("office.case_register.update")->where("id", "[0-9]+");
        Route::post('/case-register/edit/{id}', [Office\CaseRegisterController::class, "caseUpdatePost"])->name("office.case_register.update.post")->where("id", "[0-9]+");
        
        Route::post('/ajax/{method}', [Office\AjaxController::class, "run"])->name("office.ajax");
    });
    
    Route::get("/logowanie", [Office\LoginController::class, "login"])->name("office.login");
    Route::post("/logowanie", [Office\LoginController::class, "loginPost"])->name("office.login.post");
});