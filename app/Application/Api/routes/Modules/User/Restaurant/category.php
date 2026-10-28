<?php

use App\Application\Api\Controllers\User\Restaurant\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'category'],function (){
    Route::get('fetch',[CategoryController::class,'fetch'])->middleware('throttle:90,1');
    Route::get('/main/fetch',[CategoryController::class,'fetchMain'])->middleware('throttle:90,1');
});
