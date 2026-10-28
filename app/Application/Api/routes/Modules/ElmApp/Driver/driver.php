<?php


use App\Application\Api\Controllers\ElmApp\Driver\DriverController;
use Illuminate\Support\Facades\Route;




Route::group(['middleware' => ['auth:admin-api','throttle:90,1']],function (){
    Route::get('/fetch',[DriverController::class,'fetch']);
    Route::post('/elm-update',[DriverController::class,'updateDriverInfo']);
});

