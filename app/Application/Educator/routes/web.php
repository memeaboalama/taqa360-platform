<?php

use Application\Educator\Controllers\ActivationController;
use Application\Educator\Controllers\AuthController;
use Application\Educator\Controllers\EducatorController;
use Application\Educator\Controllers\OtpController;
use Application\Educator\Controllers\PasswordController;
use Application\Educator\Controllers\Profile\Builder\ProfileBuilderController;
use Application\Educator\Controllers\RegisterController;
use Application\Educator\Controllers\VerifyController;
use Application\Educator\Livewire\Profile\Builder\ProfileBuilder;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        Route::prefix(RouteServiceProvider::EDUCATOR)->as('educator:')->group(function () {

            \Illuminate\Support\Facades\Config::set('auth.defines', 'educator');
            Route::get('/', [EducatorController::class, 'getHomePage'])
                ->middleware('auth:educator')
                ->name('dashboard');

            Route::get('/home', [EducatorController::class, 'getHomePage'])
                ->middleware('auth:educator')
                ->name('home');
            Route::get('/old-home', [EducatorController::class, 'index'])
                ->middleware('auth:educator')
                ->name('old-home');


            Route::get('/register', [RegisterController::class, 'getRegisterPage'])
                ->middleware('educator.redirect')
                ->name('register');
            Route::get('/login', [AuthController::class, 'getLoginPage'])
                ->middleware('educator.redirect')
                ->name('login');

            Route::match(['get', 'post'], '/logout', [AuthController::class, 'destroy'])
                ->name('logout');

            Route::get('/forget-password', [PasswordController::class, 'forgetPassword'])
                ->middleware('educator.redirect')
                ->name('forgetPassword');

            Route::get('/verify', [VerifyController::class, 'showVerifyForm'])
                        ->middleware('educator.redirect')
                        ->name('verify');

            // Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('verify-otp.post');
            // Route::post('/resend-otp', [OtpController::class, 'resendOtp'])->name('resend-otp');


            Route::get('/reset-password', [PasswordController::class, 'resetPassword'])
                ->middleware('educator.redirect')
                ->name('resetPassword');


            Route::group(['middleware' => ['auth:educator']], function () {
                Route::get('/verify-email', [ActivationController::class, 'verifyEmail'])->name('verify-email');

                Route::group(['middleware' => ['educator.active']], function () {
                    Route::get('/profile', [ActivationController::class, 'getProfile'])->name('profile');
                    Route::get('/profile-builder',[ProfileBuilderController::class, 'getProfileBuilder'])->name('profile.builder');
                });
            });
        });
    }
);
