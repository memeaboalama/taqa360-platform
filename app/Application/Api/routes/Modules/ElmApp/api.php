<?php

use App\Application\Api\Controllers\ElmApp\ElmAppController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'elm-app'],function (){
    require __DIR__ . '/auth.php';
    require __DIR__ . '/Merchant/api.php';
    require __DIR__ . '/Order/api.php';
    require __DIR__ . '/Driver/api.php';
    require __DIR__ . '/Company/api.php';


    Route::group(['middleware' => ['auth:admin-api','throttle:90,1']],function (){
        Route::get('/statistics',[ElmAppController::class,'index']);
    });
});
