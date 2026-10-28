<?php



/*
|--------------------------------------------------------------------------
| Web Routes - Api
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


use App\Application\Api\Controllers\User\Auth\AuthController;
use App\Application\Api\Controllers\User\Auth\PasswordResetController;
use App\Application\Api\Controllers\User\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth','middleware' => 'throttle:35,1'],function (){
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/otp/resend',[AuthController::class,'resend_otp']);
    Route::post('/otp/verify',[AuthController::class,'verify_otp']);
    Route::post('/forgot-password',[PasswordResetController::class,'forgotPassword']);
    Route::post('/verify-reset-code',[PasswordResetController::class,'verifyResetCode']);
    Route::post('/resend-reset-code',[PasswordResetController::class,'resendResetCode']);
    Route::post('/reset-password',[PasswordResetController::class,'resetPassword']);
});
Route::group(['prefix' => 'profile','middleware' => ['auth:user-api','throttle:35,1']],function (){
    Route::post('/update',[ProfileController::class,'update']);
    Route::post('/updateV2',[ProfileController::class,'updateV2']);
    Route::get('/',[ProfileController::class,'index']);

});
