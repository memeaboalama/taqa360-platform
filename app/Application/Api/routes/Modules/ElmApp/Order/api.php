<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'orders'], function () {
    require __DIR__ . '/order.php';
});
