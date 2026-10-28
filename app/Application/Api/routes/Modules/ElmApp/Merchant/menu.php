<?php

use App\Application\Api\Controllers\User\Restaurant\MenuController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'menu'],function (){
    Route::get('fetch',[MenuController::class,'fetch']);
});
