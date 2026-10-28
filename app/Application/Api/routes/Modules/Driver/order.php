<?php


use App\Application\Api\Controllers\Driver\Order\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'order', 'middleware' => ['auth:driver-api', 'throttle:35,1']], function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/details', [OrderController::class, 'show']);
    Route::post('/update', [OrderController::class, 'update']);
    Route::post('/action', [OrderController::class, 'action']);
    Route::post('/test', [OrderController::class, 'test']);
    Route::post('/ready', [OrderController::class, 'ready']);

});
