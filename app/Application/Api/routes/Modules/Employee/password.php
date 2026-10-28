<?php


use App\Application\Api\Controllers\Employee\Password\ForgetPasswordController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'password','middleware' => 'throttle:35,1'],function (){
    Route::post('/forget',[ForgetPasswordController::class,'forget']);
    Route::post('/verify',[ForgetPasswordController::class,'verify']);
    Route::post('/reset',[ForgetPasswordController::class,'reset'])->middleware('auth:employee-api');
});
