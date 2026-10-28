<?php


use App\Application\Api\Controllers\Driver\Auth\AuthController;
use App\Application\Api\Controllers\Driver\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'middleware' => 'throttle:35,1'], function () {
    Route::post('/', [AuthController::class, 'login']);
    Route::post('remove-token', [AuthController::class, 'remove_token']);
    Route::post('/register', [AuthController::class, 'register']);
});
Route::group(['prefix' => 'info', 'middleware' => ['auth:driver-api', 'throttle:35,1']], function () {

    // Route::group(['prefix' => 'info'],function (){
    Route::post('update/network_status', [ProfileController::class, 'update']);
    Route::post('update/location', [ProfileController::class, 'update_location']);
    Route::get('fetch/profile', [ProfileController::class, 'fetch']);
    // });
});
