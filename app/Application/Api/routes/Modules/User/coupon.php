<?php
use App\Application\Api\Controllers\User\Coupon\CouponController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'coupon'],function (){
    Route::post('/apply',[CouponController::class,'applyPromocode']);
    Route::post('/remove',[CouponController::class,'removePromocode']);
});
