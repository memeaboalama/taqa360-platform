<?php

use App\Application\Educator\Controllers\Api\Admin\EducatorController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'educators','middleware' => 'throttle:35,1'],function (){
    Route::get('/',[EducatorController::class,'fetch']);
    Route::patch('/{educator}/status', [EducatorController::class, 'updateStatus']);
});
