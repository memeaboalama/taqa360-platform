<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'],function (){
// Admin catalog management. Like the other v1/admin groups this was reachable
// with no token; auth:admin-api is the Sanctum guard that admin login issues.
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin-api'], function () {
    require __DIR__ . '/Catalog/api.php';
    
});
});