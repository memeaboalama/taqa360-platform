<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Application\Educator\Controllers\Api\Auth\PasswordResetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1/auth', 'middleware' => 'throttle:35,1'], function () {
    Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
    Route::post('/verify-reset-code', [PasswordResetController::class, 'verifyResetCode']);
    Route::post('/resend-reset-code', [PasswordResetController::class, 'resendResetCode']);
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
});
