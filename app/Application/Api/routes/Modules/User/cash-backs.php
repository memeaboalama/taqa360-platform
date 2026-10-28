<?php
use App\Application\Api\Controllers\User\CashBack\CashBackController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'cash-backs'],function (){
    Route::post('/apply',[CashBackController::class,'applyCashBack']);
    Route::post('/remove',[CashBackController::class,'removeCashBack']);
});
