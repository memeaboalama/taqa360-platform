<?php

use App\Application\Admin\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'users', 'middleware' => 'throttle:35,1'], function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{user}', 'show');
        Route::match(['PUT', 'PATCH'], '/{user}', 'update');
        Route::patch('/{user}/status', 'updateStatus');
        Route::delete('/{user}', 'destroy');
    });
});
