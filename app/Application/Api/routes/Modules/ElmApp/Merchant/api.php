<?php

use App\Application\Api\Controllers\ElmApp\Merchant\MerchantController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'restaurant'], function () {
    require __DIR__ . '/category.php';
    require __DIR__ . '/menu.php';


    Route::post('/fetch',[MerchantController::class,'getMerchants'])->middleware('throttle:90,1');
    Route::get('/reports',[MerchantController::class,'getOrdersReport'])->middleware('throttle:90,1');
    Route::get('/show/{merchant_id}/{branch_id}',[MerchantController::class,'show']);
    Route::get('/fetch/items/{category_id}',[MerchantController::class,'showItemsByCategory'])->middleware('throttle:90,1');
    Route::get('/fetch/item/{item_id}',[MerchantController::class,'showItem']);
});
