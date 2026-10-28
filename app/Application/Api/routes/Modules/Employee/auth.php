<?php


use App\Application\Api\Controllers\Employee\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth','middleware' => 'throttle:35,1'],function (){
    Route::post('/login',[AuthController::class,'login']);
});
