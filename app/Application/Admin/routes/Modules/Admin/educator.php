<?php

use App\Application\Admin\Controllers\Api\Educator\EducatorController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'educators','middleware' => 'throttle:35,1'], function (){

    Route::controller(EducatorController::class)->group(function (){
        Route::get('/','fetch');
        Route::post('/invite','invite');
        Route::post('/invite/complete','completeRegistration');
        Route::get('/{educator}','show');
        Route::delete('/{educator}','destroy');
        Route::patch('/{educator}/status','updateStatus');
        Route::post('/{educator}/accept','accept');
        Route::post('/{educator}/reject','reject');
        Route::post('/{educator}/academic-majors/{educatorMajor}/approve','approveAcademicMajor');
        Route::post('/{educator}/academic-majors/{educatorMajor}/reject','rejectAcademicMajor');
        Route::post('/{educator}/approve-identity','approveIdentity');
        Route::post('/{educator}/reject-identity','rejectIdentity');
        Route::post('/{educator}/suspend','suspend');
        Route::post('/{educator}/unsuspend','unsuspend');
    });
});
