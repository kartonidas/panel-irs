<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Office;
use App\Http\Controllers\Panel;
use App\Http\Middleware\AuthOffice;
use App\Http\Middleware\AuthPanel;


Route::middleware([AuthPanel::class])->group(function () {
    Route::get("/", [Panel\IndexController::class, "dashboard"])->name("office");
    Route::get("/wyloguj", [Panel\LoginController::class, "logout"])->name("logout");
    
    Route::get('/profile', [Panel\UserController::class, "profile"])->name("profile");
    Route::post('/profile', [Panel\UserController::class, "profilePost"])->name("profile.post");
});
Route::get("/logowanie", [Panel\LoginController::class, "login"])->name("login");
Route::post("/logowanie", [Panel\LoginController::class, "loginPost"])->name("login.post");


Route::prefix("kancelaria")->group(function () {
    Route::middleware([AuthOffice::class])->group(function () {
        Route::get("/", [Office\IndexController::class, "dashboard"])->name("office");
        Route::get("/wyloguj", [Office\LoginController::class, "logout"])->name("office.logout");
        Route::get("/filter/{module}", [Office\IndexController::class, "filter"])->name("office.filter");
        Route::get("/clear-filter/{module}", [Office\IndexController::class, "clearFilter"])->name("office.clear-filter");
        Route::get('/sort/{module}/{sort}/{extra?}', [Office\IndexController::class, "sort"])->name("office.sort");
        Route::get('/page-size/{module}/{size}', [Office\IndexController::class, "setPageSize"])->name("office.page-size");
        
        Route::get('/profile', [Office\OfficeUserController::class, "profile"])->name("office.profile");
        Route::post('/profile', [Office\OfficeUserController::class, "profilePost"])->name("office.profile.post");
        
        Route::get('/users', [Office\OfficeUserController::class, "list"])->name("office.users");
        Route::get('/user/new', [Office\OfficeUserController::class, "userCreate"])->name("office.user.create");
        Route::post('/user/new', [Office\OfficeUserController::class, "userCreateSave"])->name("office.user.create.post");
        Route::get('/user/edit/{id}', [Office\OfficeUserController::class, "userUpdate"])->name("office.user.update")->where("id", "[0-9]+");
        Route::post('/user/edit/{id}', [Office\OfficeUserController::class, "userUpdateSave"])->name("office.user.update.post")->where("id", "[0-9]+");
        Route::post('/user/delete/{id}', [Office\OfficeUserController::class, "userDelete"])->name("office.user.delete")->where("id", "[0-9]+");
        
        Route::get('/permissions', [Office\OfficePermissionsController::class, "list"])->name("office.permissions");
        Route::get('/permission/new', [Office\OfficePermissionsController::class, "create"])->name("office.permission.create");
        Route::post('/permission/new', [Office\OfficePermissionsController::class, "createPost"])->name("office.permission.create.post");
        Route::get('/permission/edit/{id}', [Office\OfficePermissionsController::class, "update"])->name("office.permission.update")->where("id", "[0-9]+");
        Route::post('/permission/edit/{id}', [Office\OfficePermissionsController::class, "updatePost"])->name("office.permission.update.post")->where("id", "[0-9]+");
        Route::post('/permission/delete/{id}', [Office\OfficePermissionsController::class, "delete"])->name("office.permission.delete")->where("id", "[0-9]+");

        Route::get('/customers', [Office\CustomerController::class, "list"])->name("office.customers");
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
        
        Route::get('/case-register', [Office\CaseRegisterController::class, "list"])->name("office.case_register");
        
        Route::post('/ajax/{method}', [Office\AjaxController::class, "run"])->name("office.ajax");
    });
    
    Route::get("/logowanie", [Office\LoginController::class, "login"])->name("office.login");
    Route::post("/logowanie", [Office\LoginController::class, "loginPost"])->name("office.login.post");
});