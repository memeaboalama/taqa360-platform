<?php

use App\Application\Api\Controllers\User\Restaurant\RestaurantController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'restaurant'], function () {
    require __DIR__ . '/category.php';
    require __DIR__ . '/menu.php';
    Route::post('/fetch',[RestaurantController::class,'fetch'])->middleware('throttle:90,1');
    Route::post('/fetch-pack-up',[RestaurantController::class,'fetchPackUp'])->middleware('throttle:90,1');
    Route::get('/show/{merchant_id}/{branch_id}',[RestaurantController::class,'show']);
    Route::get('/fetch/items/{category_id}',[RestaurantController::class,'showItemsByCategory'])->where('category_id', '[0-9]+')->middleware('throttle:90,1');
    Route::get('/fetch/item/{item_id}',[RestaurantController::class,'showItem']);
});
