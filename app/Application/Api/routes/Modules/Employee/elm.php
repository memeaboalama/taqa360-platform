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


use App\Application\Api\Controllers\User\Address\AddressController;
use App\Application\Api\Controllers\User\Order\ElmOrderController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'elmOrder','middleware' => ['auth:employee-api','throttle:90,1']],function (){
    Route::get('/fetch/{id}',[ElmOrderController::class,'fetch']);
    Route::put('/update',[ElmOrderController::class,'update']);
});
