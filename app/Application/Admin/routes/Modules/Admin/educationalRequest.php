<?php

use App\Application\Admin\Controllers\Api\EducationalRequest\EducationalController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'educational-requests','middleware' => 'throttle:35,1'], function (){

    Route::controller(EducationalController::class)->group(function (){
        Route::get('/','fetch');
        Route::post('/','store');
        Route::get('/{educationalRequest}','show');
        Route::put('/{educationalRequest}','update');
        Route::delete('/{educationalRequest}','destroy');
        Route::post('/{educationalRequest}/change-status','changeStatus');
    });
});
