<?php

use App\Application\Admin\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/notifications', [NotificationController::class, 'index'])
    ->middleware('throttle:35,1');
