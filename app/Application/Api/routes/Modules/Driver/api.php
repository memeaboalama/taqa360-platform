<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'driver'],function (){
    require __DIR__  . '/auth.php';
    require __DIR__  . '/order.php';
    require __DIR__  . '/profile.php';
});
