<?php

use App\Application\Ecommerce\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


// Route::group(['prefix' => 'v1/admin/'],function (){
//     require __DIR__ . '/Modules/Admin/api.php';
// });



Route::group(['prefix' => 'v1/ecommerce'],function () {

    Route::prefix('products')->controller(ProductController::class)->group(function(){
        Route::get('/', 'fetch');
        Route::get('/{product_id}', 'show');
    });
});
