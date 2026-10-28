<?php

use Application\Driver\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use Application\Driver\Controllers\AuthController;
use Application\Driver\Controllers\RoleController;

use Application\Driver\Controllers\MenuController;
use Application\Driver\Controllers\DriverController;
use Application\Driver\Controllers\PasswordController;
use Application\Driver\Controllers\FileController;
use Application\Driver\Controllers\RegisterController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Application\Driver\Controllers\PoliciesController;
use Application\Driver\Controllers\Finance\Reports\Orders\OrderReportController;

/*
|--------------------------------------------------------------------------
| Web Routes - Driver
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

    Route::group(
        [
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
        ],
        function () {
        Route::prefix(RouteServiceProvider::DRIVER)->as('driver:')->group(function () {


            Route::get('/privacy-policy', [PoliciesController::class, 'index'])
                ->name('privacyPolicy');

            Route::get('/terms-conditions', [PoliciesController::class, 'terms'])
                ->name('termsConditions');


        
            \Illuminate\Support\Facades\Config::set('auth.defines', 'driver');
            Route::get('/register', [RegisterController::class, 'getRegisterPage'])
                ->middleware('driver.redirect')
                ->name('register');
            Route::get('/login', [AuthController::class, 'getLoginPage'])
                ->middleware('driver.redirect')
                ->name('login');

            Route::get('/welcome', [WelcomeController::class, 'getWelcomePage'])
                ->middleware('driver.redirect')
                ->name('welcome');

            Route::get('/file', [FileController::class, 'getFilePage'])
                ->middleware('driver.redirect')
                ->name('file');

            Route::get('/forget-password', [PasswordController::class, 'forgetPassword'])
                ->middleware('driver.redirect')
                ->name('forgetPassword');

            Route::get('/verify-otp', [PasswordController::class, 'verifyOTP'])
                ->middleware('driver.redirect')
                ->name('verifyOTP');

            Route::get('/reset-password', [PasswordController::class, 'resetPassword'])
                ->middleware('driver.redirect')
                ->name('resetPassword');



            Route::group(['middleware' => ['driver:driver']], function () {
                Route::get('/home', [DriverController::class, 'getHomePage'])
                    ->name('home');
                Route::post('/orders/{id}/accept', [DriverController::class, 'acceptOrder'])
                    ->name('orders.accept');
                Route::post('/orders/{id}/update-status', [DriverController::class, 'updateOrderStatus'])
                    ->name('orders.updateStatus');
            });
        });
    }
);
