<?php

use App\Application\Api\Controllers\User\Cart\CartController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'cart', 'middleware' => 'throttle:90,1'], function () {
    Route::post('/add_item', [CartController::class, 'addItem']);
    Route::post('/remove_item', [CartController::class, 'removeItem']);
    Route::get('/fetch', [CartController::class, 'index']);
    //    Route::get('/items/fetch',[CartController::class,'getItems']);
    Route::post('/clear', [CartController::class, 'clearItems']);
    Route::put('/qty/update', [CartController::class, 'updateQty']);
    Route::put('/service_type/update', [CartController::class, 'updateCartTypeService']);
    /** 
     * service-type
     */
    Route::delete('/service-type', [CartController::class, 'clearCartTypeServiceItems']);
    Route::put('/service-type/change', [CartController::class, 'changeCartTypeService']);
    Route::get('/service-type/fetch', [CartController::class, 'fetchCartTypeService']);
    Route::get('/service-type/items', [CartController::class, 'fetchCartTypeServiceItems']);
    Route::post('/service-type/items', [CartController::class, 'addCartTypeServiceItem']);
    Route::delete('/service-type/items', [CartController::class, 'removeCartTypeServiceItem']);
    Route::put('/service-type/items/qty', [CartController::class, 'changeCartTypeServiceItemQty']);
});
