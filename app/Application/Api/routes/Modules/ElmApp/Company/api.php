<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'company'], function () {
    require __DIR__ . '/company.php';
});
