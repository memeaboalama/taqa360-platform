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
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'address','middleware' => ['auth:user-api','throttle:90,1']],function (){
    Route::get('/fetch',[AddressController::class,'fetch']);
    Route::get('/show/{id}',[AddressController::class,'show']);
    Route::post('/store',[AddressController::class,'store']);
    Route::put('/update/{id}',[AddressController::class,'update']);
    Route::delete('/delete/{id}',[AddressController::class,'delete']);
});
