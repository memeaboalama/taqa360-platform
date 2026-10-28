<?php

use App\Application\Api\Controllers\User\Favourite\FavouriteController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'favourites'],function (){
    Route::post('/add',[FavouriteController::class,'add'])->middleware('throttle:90,1');
    Route::get('/all',[FavouriteController::class,'index'])->middleware('throttle:90,1');
    Route::get('/all_V2',[FavouriteController::class,'index_V2'])->middleware('throttle:90,1');
    Route::delete('/remove',[FavouriteController::class,'remove']);
});
