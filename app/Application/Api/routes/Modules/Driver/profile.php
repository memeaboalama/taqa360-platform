<?php


use App\Application\Api\Controllers\Driver\Auth\AuthController;
use App\Application\Api\Controllers\Driver\Profile\ProfileController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:driver-api'],function (){
    Route::group(['prefix' => 'profile'],function (){
        Route::post('update',[ProfileController::class,'update_info']);
        Route::post('complete',[ProfileController::class,'complete']);
        Route::post('change-password',[ProfileController::class,'change_password']);
        Route::post('bank-info',[ProfileController::class,'bank_info']);

    });
});
