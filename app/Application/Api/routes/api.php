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

use App\Application\Api\Controllers\BankController;
use App\Application\Api\Controllers\CarsTypesController;
use App\Application\Api\Controllers\LocationController;
use App\Application\Api\Controllers\CashBacksController;
use App\Application\Api\Controllers\LanguageController;
use App\Application\Api\Controllers\NationalityController;
use App\Application\Api\Controllers\UserPoliciesController;
use App\Application\Api\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'v1'],function (){
    require __DIR__ .'/Modules/User/api.php';
    require __DIR__ .'/Modules/Employee/api.php';
    require __DIR__ .'/Modules/Driver/api.php';
    require __DIR__ .'/Modules/ElmApp/api.php';
    Route::get('/bank',[BankController::class,'fetch']);
    Route::get('/regions',[LocationController::class,'getRegions']);
    Route::get('/countries',[LocationController::class,'getCountries']);
    Route::get('/country-cities/{country}',[LocationController::class,'getCountryCities']);
    Route::get('/cities',[LocationController::class,'getCities']);
    Route::get('/nationalities',[NationalityController::class,'getNationalities']);
    Route::get('/languages',[LanguageController::class,'getLanguages']);
    Route::get('/cash-backs',[CashBacksController::class,'getCashBacks']);
    Route::get('/cash-backs-type-service/{type_service_id}',[CashBacksController::class,'getCashBacksByTypeServiceId']);
    Route::get('/user-policies',[UserPoliciesController::class,'getUserPolicies']);
    Route::get('/cash-backs/{id}',[CashBacksController::class,'getCashBacksByMerchantId']);
    Route::get('/cash-backs/{id}/{type_service_id}',[CashBacksController::class,'getCashBacksByMerchantIdAndTypeServiceId']);
    Route::get('/cars-types',[CarsTypesController::class,'fetch']);
    Route::get('/send-notification',[NotificationController::class,'sendPushNotification']);

});


Route::group(['prefix' => 'v2'],function (){
    require __DIR__ .'/Modules/v2/Driver/api.php';
    require __DIR__ .'/Modules/v2/User/api.php';
});

