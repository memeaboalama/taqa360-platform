<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'driver'], function () {
    require __DIR__ . '/driver.php';
});
