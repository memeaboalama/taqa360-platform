<?php

use Application\Merchant\Controllers\MerchantController;
use App\Application\Admin\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;



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


// Lives outside the v1/admin group on purpose: the SPA calls it *before* it has
// a usable access token, so it must stay unauthenticated (and is rate limited).
Route::group(['prefix' => 'v1'], function () {
    Route::post('auth/refresh', [AuthController::class, 'refresh'])
        ->middleware('throttle:35,1');
});

// Everything under v1/admin is an admin-panel endpoint and used to be reachable
// with no token at all. auth:admin-api is the Sanctum guard that login issues.
// Modules/Admin/auth.php opts out via withoutMiddleware so login and the
// password-reset flow keep working.
Route::group(['prefix' => 'v1/admin/','middleware' => 'auth:admin-api'],function (){
    require __DIR__ . '/Modules/Admin/auth.php';
    require __DIR__ . '/Modules/Admin/educationalRequest.php';
    require __DIR__ . '/Modules/Admin/edu.php';
    require __DIR__ . '/Modules/Admin/educator.php';
    require __DIR__ . '/Modules/Admin/user.php';
});
