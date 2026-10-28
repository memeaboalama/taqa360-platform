<?php

use App\Application\Api\Controllers\v2\User\Merchant\MerchantController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'merchants'], function () {
    // require __DIR__ . '/category.php';
    // require __DIR__ . '/menu.php';
    Route::post('/fetch',[MerchantController::class,'fetch'])->middleware('throttle:90,1');
    Route::post('/fetch-pack-up',[MerchantController::class,'fetchPackUp'])->middleware('throttle:90,1');
    Route::get('/show/{merchant_id}',[MerchantController::class,'show']);
    // Route::get('/fetch/items/{category_id}',[MerchantController::class,'showItemsByCategory'])->where('category_id', '[0-9]+')->middleware('throttle:90,1');
    // Route::get('/fetch/item/{item_id}',[MerchantController::class,'showItem']);
});
