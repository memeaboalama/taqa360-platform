<?php


use App\Application\Api\Controllers\Employee\Order\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'orders', 'middleware' => ['auth:employee-api', 'throttle:90,1']], function () {
    Route::get('/all', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::post('/accept', [OrderController::class, 'accept']);
    Route::post('/reject', [OrderController::class, 'reject']);
    Route::post('/start', [OrderController::class, 'start']);
    Route::post('/mark_ready', [OrderController::class, 'mark_ready']);
    Route::post('/mark_complete', [OrderController::class, 'mark_complete']);
});
