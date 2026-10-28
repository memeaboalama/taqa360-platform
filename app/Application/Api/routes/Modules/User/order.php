<?php


/*
|--------------------------------------------------------------------------
| Web Routes - Api
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


use App\Application\Api\Controllers\User\Order\OrderController;
use App\Application\Api\Controllers\User\Order\OrderServiceTypeController;
use App\Application\Api\Controllers\User\Order\RatingController;

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'orders', 'middleware' => ['auth:user-api', 'throttle:90,1']], function () {
    Route::post('/place', [OrderController::class, 'store']);
    Route::post('/placeV2', [OrderController::class, 'storeV2']);
    Route::post('/cancel', [OrderController::class, 'cancel']);
    Route::get('/all', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::group(['prefix' => 'rate'], function () {
        Route::post('/food', [RatingController::class, 'rateFood']);
        Route::post('/driver', [RatingController::class, 'rateDriver']);
    });
    Route::group(['prefix' => 'service-type'], function () {
        Route::post('/place', [OrderServiceTypeController::class, 'store']);
        Route::post('/cancel', [OrderServiceTypeController::class, 'cancel']);
        Route::get('/all', [OrderServiceTypeController::class, 'index']);
        Route::get('/{id}', [OrderServiceTypeController::class, 'show']);
    });
});
Route::post('order/payment/create',[OrderController::class,'createPayment'])->name('order.payment.create');
