<?php

use App\Application\Api\Controllers\ElmApp\Order\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:admin-api','throttle:90,1']],function (){
    Route::post('update-status',[OrderController::class,'updateOrderStatus']);
    Route::post('assign-driver',[OrderController::class,'manuallyAssignDriver']);
    Route::post('/elm-update',[OrderController::class,'updateOrderInfo']);
    Route::get('/fetch',[OrderController::class,'fetch']);
});


