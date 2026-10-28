<?php

// use App\Application\Api\Controllers\Admin\Catalog\SpecificationController;
// use App\Application\Api\Controllers\Admin\Catalog\MasterSpecificationController;
// use App\Application\Api\Controllers\Admin\Catalog\AttachmentController;
use App\Domain\Catalog\Controllers\SpecificationController;
use App\Domain\Catalog\Controllers\MasterSpecificationController;
use App\Domain\Catalog\Controllers\AttachmentController;
use Illuminate\Support\Facades\Route;
Route::group(['prefix' => 'catalog'], function () {
    
    
    // Route::apiResource('specifications', SpecificationController::class);
    Route::group(['prefix' => 'specifications'], function () {
        Route::get('/', [SpecificationController::class, 'index']);
        Route::post('/', [SpecificationController::class, 'store']);
        Route::get('/{specification}', [SpecificationController::class, 'show']);
        Route::put('/{specification}', [SpecificationController::class, 'update']);
        Route::delete('/{specification}', [SpecificationController::class, 'destroy']);
    });
    Route::get('master-specifications', [MasterSpecificationController::class, 'index']);
    Route::post('master-specifications/attach-to-item', [MasterSpecificationController::class, 'attachToItem']);
    

    // Routes for attachments nested under an item
    // Route::apiResource('items.attachments', AttachmentController::class)->only(['index', 'store']);

    Route::group(['prefix' => 'items'], function () {
        Route::group(['prefix' => '{item}/attachments'], function () {
            Route::get('/', [AttachmentController::class, 'index']);
            Route::post('/', [AttachmentController::class, 'store']);
            Route::post('reorder', [AttachmentController::class, 'reorder']);
            Route::post('attach-existing', [AttachmentController::class, 'attachExisting']);
            Route::post('{attachment}/set-primary', [AttachmentController::class, 'setPrimary']);
            Route::post('detach-existing', [AttachmentController::class, 'detachExisting']);
        });
        
        // Route::post('attachments/reorder', [AttachmentController::class, 'reorder']);
        // Route::post('attachments/attach-existing', [AttachmentController::class, 'attachExisting']);
        // Route::post('attachments/{attachment}/set-primary', [AttachmentController::class, 'setPrimary']);
    });
    // Routes for managing a specific attachment by its ID
    // Route::apiResource('attachments', AttachmentController::class)->only(['update', 'destroy']);
    Route::group(['prefix' => 'attachments'], function () {
        // Route::get('/', [AttachmentController::class, 'index']);
        // Route::post('/', [AttachmentController::class, 'store']);
        // Route::get('/{attachment}', [AttachmentController::class, 'show']);
        Route::get('/library', [AttachmentController::class, 'library']);
        Route::delete('/bulk-delete', [AttachmentController::class, 'bulkDelete']);
        Route::put('/{attachment}', [AttachmentController::class, 'update']);
        Route::delete('/{attachment}', [AttachmentController::class, 'destroy']);
    });
});