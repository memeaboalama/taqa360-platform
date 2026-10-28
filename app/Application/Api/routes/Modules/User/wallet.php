<?php

use App\Application\Api\Controllers\User\Wallet\MerchantsWalletsController;
use App\Application\Api\Controllers\User\Wallet\WalletController;
use App\Application\Api\Controllers\User\Wallet\WalletCouponController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'walletCoupon'],function (){
    Route::post('/apply',[WalletCouponController::class,'applyWalletCoupon']);
    // Route::post('/remove',[WalletCouponController::class,'removeWalletCoupon']);
});

Route::group(['prefix' => 'wallet'],function (){
    Route::get('/fetch',[WalletController::class,'fetch']);
    Route::post('/deposit',[WalletController::class,'deposit']);
    Route::get('/history',[WalletController::class,'getWalletTransaction']);

});

Route::group(['prefix' => 'merchantsWallets'],function (){
    Route::get('/fetch',[MerchantsWalletsController::class,'fetch']);
});


