<?php


use App\Application\Api\Controllers\v2\Driver\Auth\AuthController;
use App\Application\Api\Controllers\Driver\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth','middleware' => 'throttle:35,1'],function (){
    Route::post('/',[AuthController::class,'login']);
    Route::post('remove-token',[AuthController::class,'remove_token']);
    Route::post('/register',[AuthController::class,'register']);

});
