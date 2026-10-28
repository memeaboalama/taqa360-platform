<?php


use App\Application\Api\Controllers\Driver\Profile\LocationController;
use Application\Marketplace\Controllers\OrderPaymentController;
use Application\Marketplace\Controllers\WalletDepositController;
use Illuminate\Support\Facades\Route;
use App\Application\Api\Controllers\ContactUsController;
use App\Application\Api\Controllers\User\BannerController;
use App\Application\Api\Controllers\User\SearchHistoryController;
use Application\Marketplace\Controllers\Payment\OrderPaymentClickPayController;

// Route::post('contact-us/send', [ContactUsController::class, 'send']);
// Route::get('banners/fetch', [BannerController::class, 'fetch']);
Route::group(['prefix' => 'user'], function () {
    // require __DIR__  . '/auth.php';
    // require __DIR__  . '/address.php';
    require __DIR__ . '/Merchant/api.php';
    // Route::group(['middleware' => ['auth:user-api', 'throttle:90,1']], function () {
    //     require __DIR__ . '/cart.php';
    //     require __DIR__ . '/coupon.php';
    //     require __DIR__ . '/favourite.php';
    //     require __DIR__ . '/wallet.php';
    //     require __DIR__ . '/order.php';
    //     require __DIR__ . '/elm.php';
    //     require __DIR__ . '/cash-backs.php';

    //     Route::group(['prefix' => 'driver'], function () {
    //         Route::get('/fetch-location', [LocationController::class, 'fetch']);
    //     });

    //     Route::get('/search/history/fetch', [SearchHistoryController::class, 'fetch']);
    //     Route::delete('/search/history/remove/{id}', [SearchHistoryController::class, 'remove']);
    //     Route::delete('/search/history/clear', [SearchHistoryController::class, 'clear']);
    // });
});
// Route::post('order/payment/save', [OrderPaymentController::class, 'payment_save'])->name('order.payment.save');
// Route::post('wallet/payment/save', [WalletDepositController::class, 'payment_save'])->name('wallet.payment.save');
// Route::post('order/payment/click-pay/status', [OrderPaymentClickPayController::class, 'payment_status'])
//     ->name('order.payment.clickpay.status');
// Route::post('order/payment/click-pay/call-back', [OrderPaymentClickPayController::class, 'payment_ipn'])
//     ->name('order.payment.clickpay.callback');
