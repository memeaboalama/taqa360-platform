<?php

use App\Application\Admin\Controllers\Api\Auth\AuthController;
use App\Application\Admin\Controllers\Api\Auth\PasswordResetController;
use Illuminate\Support\Facades\Route;

// withoutMiddleware: the parent v1/admin group now requires auth:admin-api, but
// these routes are how a caller obtains a token in the first place.
Route::group(['prefix' => 'auth','middleware' => 'throttle:35,1','withoutMiddleware' => 'auth:admin-api'],function (){
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/forgot-password',[PasswordResetController::class,'forgotPassword']);
    Route::post('/verify-reset-code',[PasswordResetController::class,'verifyResetCode']);
    Route::post('/resend-reset-code',[PasswordResetController::class,'resendResetCode']);
    Route::post('/reset-password',[PasswordResetController::class,'resetPassword']);
});
