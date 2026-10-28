<?php

use Application\Company\Controllers\RegisterController;
use Application\Company\Controllers\AuthController;
use Application\Company\Controllers\CompanyController;
use Application\Company\Controllers\DriverController;
use Application\Company\Controllers\OrderController;
use Application\Company\Controllers\RoleController;
use Application\Company\Controllers\EmployeeController;
use Application\Company\Controllers\PasswordController;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;




/*
|-------------------------------------------------------------------------
| Web Routes - Company
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
    ], function () {
    Route::prefix(RouteServiceProvider::COMPANY)->as('company:')->group(function () {
        \Illuminate\Support\Facades\Config::set('auth.defines', 'company');
        Route::get('/register', [RegisterController::class, 'getRegisterPage'])
            ->middleware('company.redirect')
            ->name('register');
        Route::get('/login', [AuthController::class, 'getLoginPage'])
            ->middleware('company.redirect')
            ->name('login');

        Route::get('/forget-password', [PasswordController::class, 'forgetPassword'])
            ->middleware('company.redirect')
            ->name('forgetPassword');

        Route::get('/verify-otp', [PasswordController::class, 'verifyOTP'])
            ->middleware('company.redirect')
            ->name('verifyOTP');

        Route::get('/reset-password', [PasswordController::class, 'resetPassword'])
            ->middleware('company.redirect')
            ->name('resetPassword');

        Route::group(['middleware'=>['company:company']], function (){
            Route::get('/home', [CompanyController::class, 'index'])->name('home');
            Route::prefix('drivers')->as('drivers.')->group(function () {
                Route::get('/', [DriverController::class, 'index']);
                Route::get('/show/{id}', [DriverController::class, 'show'])->name('show');
                Route::get('/tracking', [DriverController::class, 'trackDrivers']);
            });
            Route::get('/roles', [RoleController::class, 'index'])
                ->name('roles');

            Route::get('/employees', [EmployeeController::class, 'index'])
                ->name('employees');
            Route::get('/drivers', [DriverController::class, 'index'])->name('drivers');
            Route::get('/orders', [OrderController::class, 'index'])->name('orders');
            Route::get('/orders/{id}', [OrderController::class, 'showOrder'])->name('showOrder');
        });
    });
});
