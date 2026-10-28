<?php

use App\Application\Admin\Controllers\Api\Edu\AcademicMajorController;
use App\Application\Admin\Controllers\Api\Edu\ServiceController;
use App\Application\Admin\Controllers\Api\Edu\SkillController;
use Illuminate\Support\Facades\Route;


Route::prefix('edu')->group(function () {
    Route::apiResource('academic-majors', AcademicMajorController::class);
    Route::post('academic-majors/{academicMajor}/status-toggle', [AcademicMajorController::class, 'statusToggle']);
    Route::apiResource('services', ServiceController::class);
    Route::post('services/{service}/status-toggle', [ServiceController::class, 'statusToggle']);
    Route::apiResource('skills', SkillController::class);
    Route::post('skills/{skill}/status-toggle', [SkillController::class, 'statusToggle']);
});
