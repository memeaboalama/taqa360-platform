<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'employee'],function (){
    require __DIR__  . '/auth.php';
    require __DIR__  . '/password.php';
    require __DIR__  . '/order.php';
    require __DIR__  . '/menu.php';
    require __DIR__  . '/elm.php';
});
