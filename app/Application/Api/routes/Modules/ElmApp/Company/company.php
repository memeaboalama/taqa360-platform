<?php


use App\Application\Api\Controllers\ElmApp\Company\CompanyController;
use Illuminate\Support\Facades\Route;




Route::group(['middleware' => ['auth:admin-api','throttle:90,1']],function (){
    Route::get('/fetch',[CompanyController::class,'fetch']);
});

