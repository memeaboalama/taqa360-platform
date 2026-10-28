<?php


use App\Application\Api\Controllers\User\Order\ElmOrderController;
use App\Application\Api\Controllers\v2\Driver\Elm\ElmInfoController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:driver-api'],function (){
    Route::group(['prefix' => 'elm/info'],function (){
        Route::post('update',[ElmInfoController::class,'update']);
        Route::get('fetch',[ElmInfoController::class,'fetch']);
    });


    Route::group(['prefix' => 'elmOrder'],function (){
        Route::get('/fetch/{id}',[ElmOrderController::class,'fetch']);
        Route::put('/update',[ElmOrderController::class,'update']);
    });
});

