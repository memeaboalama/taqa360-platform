<?php

use Application\Marketplace\Controllers\CouponController;
use Application\Marketplace\Controllers\WalletDepositController;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use Application\Marketplace\Controllers\AuthController;
use Application\Marketplace\Controllers\ItemController;
use Application\Marketplace\Controllers\RoleController;
use Application\Marketplace\Controllers\OrderController;
use Application\Marketplace\Controllers\BranchController;
use Application\Marketplace\Controllers\ChoiceController;
use Application\Marketplace\Controllers\MenuController;
use Application\Marketplace\Controllers\CategoryController;
use Application\Marketplace\Controllers\EmployeeController;
use Application\Marketplace\Controllers\MerchantController;
use Application\Marketplace\Controllers\PasswordController;

use Application\Marketplace\Controllers\RegisterController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Application\Marketplace\Controllers\ActivationController;
use Application\Marketplace\Controllers\OrderPaymentController;
use Application\Marketplace\Controllers\AdditionGroupController;
use Application\Marketplace\Controllers\TermsController;
use Application\Marketplace\Controllers\PoliciesController;
use Application\Marketplace\Controllers\MessagesController;
use Application\Marketplace\Controllers\CashBackController;
use Application\Marketplace\Controllers\Finance\Reports\Orders\OrderReportController;
use Application\Marketplace\Controllers\Payment\OrderPaymentClickPayController;
use Application\Marketplace\Controllers\ProductController;
use Application\Marketplace\Controllers\UploadController;


/*
|--------------------------------------------------------------------------
| Web Routes - Merchant
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::post('/photo/uploads', [UploadController::class, 'uploads']);

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        Route::prefix(RouteServiceProvider::MARKETPLACE)->as('marketplace:')->group(function () {

            Route::get('/terms-conditions', [TermsController::class, 'index'])
                ->name('termsConditions');

            Route::get('/privacy-policy', [PoliciesController::class, 'index'])
                ->name('privacyPolicy');


            Route::get('order/payment/iframe/{lang}/{token}', [OrderPaymentController::class, 'paymentIframe'])
                ->name('order.payment.iframe');
            Route::get('order/payment/check', [OrderPaymentController::class, 'payment_check'])->name('order.payment.check');
            Route::get('order/payment/status/{status}/{Id}', [OrderPaymentController::class, 'payment_status'])->name('order.payment.status');


            Route::get('wallet/payment/iframe/{lang}/{token}', [WalletDepositController::class, 'paymentIframe'])
                ->name('wallet.payment.iframe');
            Route::get('wallet/payment/check', [WalletDepositController::class, 'payment_check'])->name('wallet.payment.check');
            Route::get('wallet/payment/status/{status}/{Id}', [WalletDepositController::class, 'payment_status'])->name('wallet.payment.status');


            /****
             * Click PAY Payment
             *
             */

            Route::get('order/payment/click-pay/{lang}/{token}', [OrderPaymentClickPayController::class, 'paymentIframe'])
                ->name('order.payment.clickpay.iframe');
            // Route::get('order/payment/click-pay/check', [OrderPaymentClickPayController::class, 'payment_check'])
            // ->name('order.payment.clickpay.check');
            Route::get('order/payment/click-pay/{id}', [OrderPaymentClickPayController::class, 'return_payment_status'])
                ->name('order.payment.clickpay.check');




            /****
             *
             *
             */


            \Illuminate\Support\Facades\Config::set('auth.defines', 'merchant');
            Route::get('/register', [RegisterController::class, 'getRegisterPage'])
                ->middleware('merchant.redirect')
                ->name('register');
            Route::get('/login', [AuthController::class, 'getLoginPage'])
                ->middleware('merchant.redirect')
                ->name('login');
            Route::post('/login', [AuthController::class, 'login'])
                ->middleware('merchant.redirect')
                ->name('login.submit');

            Route::get('/forget-password', [PasswordController::class, 'forgetPassword'])
                ->middleware('merchant.redirect')
                ->name('forgetPassword');

            Route::get('/verify-otp', [PasswordController::class, 'verifyOTP'])
                ->middleware('merchant.redirect')
                ->name('verifyOTP');

            Route::get('/reset-password', [PasswordController::class, 'resetPassword'])
                ->middleware('merchant.redirect')
                ->name('resetPassword');



            Route::group(['middleware' => ['merchant:merchant']], function () {
                Route::get('/verify-email', [ActivationController::class, 'verifyEmail'])
                    ->name('verify-email');
                Route::group(['middleware' => ['merchant.active']], function () {
                    Route::get('/profile', [ActivationController::class, 'getProfile'])
                        ->name('profile');

                    Route::get('/support', [MessagesController::class, 'sendMessage'])
                        ->middleware('merchant.check_permission:contact support_general')
                        ->name('sendMessage');
                    Route::get('/forget-account-password', [ActivationController::class, 'forgetPassword'])
                        ->middleware('merchant.check_permission:forget_password')
                        ->name('forgetAccPassword');
                    Route::post('/updateFiles', [ActivationController::class, 'uploadFiles'])
                        ->middleware('merchant.check_permission:update_files')
                        ->name('updateFiles');
                    Route::get('/branches', [BranchController::class, 'index'])
                        ->middleware('merchant.check_permission:show_branches')
                        ->name('branches');
                    Route::get('/branches/{id}', [BranchController::class, 'show'])->name('branches.show');
                    Route::get('/additions-groups', [AdditionGroupController::class, 'index'])
                        ->middleware('merchant.check_permission:show_menu addition groups')
                        ->name('additions-groups');
                    Route::get('/additions-groups/{id}', [AdditionGroupController::class, 'show'])->name('additions-groups.show');

                    Route::get('/choices', [ChoiceController::class, 'index'])
                        ->middleware('merchant.check_permission:show_menu choices')
                        ->name('choices');

                    Route::get('/menu', [MenuController::class, 'index'])
                        ->middleware('merchant.check_permission:show_menu')
                        ->middleware('merchant.menu.initialization')
                        ->name('services');

                    Route::get('/menu/{id}', [MenuController::class, 'showMenu'])->name('showMenu');

                    Route::get('/orders/{status?}', [OrderController::class, 'index'])
                        ->middleware('merchant.check_permission:show orders_general')
                        ->name('orders');

                    Route::get('/show/order/{id}', [OrderController::class, 'showOrder'])->name('showOrder');
                    Route::get('/employees', [EmployeeController::class, 'index'])
                        ->middleware('merchant.check_permission:show_employees')
                        ->name('employees');

                    Route::get('/products', [ProductController::class, 'index'])
                        // ->middleware('merchant.check_permission:show_products')
                        ->name('products');

                    Route::get('/coupons', [CouponController::class, 'index'])
                        ->middleware('merchant.check_permission:show_employees')
                        ->name('coupons');

                    Route::get('/cashbacks', [CashBackController::class, 'index'])
                        ->middleware('merchant.check_permission:show_employees')
                        ->name('cashbacks');

                    Route::get('/roles', [RoleController::class, 'index'])
                        ->middleware('merchant.check_permission:show_roles')
                        ->name('roles');

                    Route::get('/home', [MerchantController::class, 'index'])
                        ->name('home');

                    Route::prefix('finance')->as('finance.')->group(function () {
                        Route::prefix('reports')->as('reports.')->group(function () {
                            Route::prefix('orders')->as('orders.')->group(function () {
                                Route::get('/', [OrderReportController::class, 'index'])->name('index');
                            });
                        });
                    });
                });
            });
        });
    }
);
