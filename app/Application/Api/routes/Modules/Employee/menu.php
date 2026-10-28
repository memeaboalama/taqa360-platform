<?php


use App\Application\Api\Controllers\Employee\Order\MenuController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'menu', 'middleware' => ['auth:employee-api', 'throttle:35,1']], function () {
    Route::get('/categories/fetch', [MenuController::class, 'getCategories']);
    Route::get('items/{id}', [MenuController::class, 'showItemsByCategory']);
    Route::post('items/change/status', [MenuController::class, 'changeItemStatus']);
});
