<?php

use App\Application\Educational\Controllers\Api\EducationalController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/user'],function (){

    Route::prefix('educational-requests')->controller(EducationalController::class)->group(function (){
        Route::get('/','fetch');
        Route::post('/','store');
        Route::get('/{request}','show');
        Route::put('/{request}','update');
        Route::delete('/{request}','destroy');
        Route::post('/{request}/change-status','changeStatus');
    });
});
