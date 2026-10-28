<?php

use App\Application\Admin\Controllers\Api\Auth\AuthController;
use App\Application\Project\Controllers\Api\BidController;
use Application\Project\Controllers\Api\AssignmentController;
use App\Application\Project\Controllers\Api\BidTaskController;
use Application\Project\Controllers\Api\AttachmentController;
use Application\Project\Controllers\Api\ProjectController;
use Application\Project\Controllers\Api\TaskController;
use Application\Project\Controllers\Api\SubTaskController;
use Illuminate\Support\Facades\Route;

// Everything under v1/admin is an admin-panel endpoint and used to be reachable
// with no token at all. auth:admin-api is the Sanctum guard that login issues;
// the auth/login group below opts out again via withoutMiddleware.
Route::group(['prefix' => 'v1/admin', 'middleware' => 'auth:admin-api'],function () {

    Route::prefix('auth')->withoutMiddleware('auth:admin-api')->controller(AuthController::class)->group(function() {
        Route::post('/login', 'login');
    });

    Route::prefix('bids')->group(function () {

        Route::controller(BidController::class)->group(function () {
            Route::get('/{bid}', 'show');
        });
    });

    Route::prefix('projects')->group(function () {

        Route::controller(ProjectController::class)->group(function () {
            Route::get('/','fetch');
            Route::post('/','store');
            Route::get('/{project}','show');
            Route::put('/{project}','update');
            Route::delete('/{project}','destroy');
            Route::post('/{project}/change-status','changeStatus');
            Route::post('/{project}/publish', 'publish');
            Route::post('/{project}/unpublish', 'unpublish');
            Route::post('/{project}/upload-attachment', 'uploadAttachment');
        });

        Route::prefix('{project}/attachments')->controller(AttachmentController::class)->group(function () {
            Route::get('/', 'fetch');
            Route::post('/', 'store');
            Route::put('/{attachment}', 'update');
            Route::delete('/{attachment}', 'destroy');
        });

        // Deliverables the specialists uploaded from educator/taskDetail. They
        // hang off the task (task_files) so the project attachment list above
        // never included them.
        Route::prefix('{project}/deliverables')->controller(AttachmentController::class)->group(function () {
            Route::get('/', 'deliverables');
        });

    });


    // Admin assignment status management, called from admin/projectDetail.
    // Auth comes from the parent v1/admin group (auth:admin-api).
    Route::prefix('assignments')->controller(AssignmentController::class)->group(function () {
        Route::post('/{assignment}/accept', 'accept');
        Route::post('/{assignment}/reject', 'reject');
        Route::patch('/{assignment}/status', 'updateStatus');
    });
    Route::prefix('tasks')->group(function () {

        Route::controller(TaskController::class)->group(function (){
            Route::post('/','store');
            Route::get('/{task}','show');
            Route::put('/{task}','update');
            Route::delete('/{task}','destroy');
            Route::post('/{task}/change-status','changeStatus');
            Route::post('/{task}/notes', 'storeTaskNote');
            Route::post('/{task}/assignments','assignEducators');
            Route::post('/{task}/assign','assignEducators');
            Route::post('{task}/open-bidding', 'openBidding');
            Route::post('{task}/close-bidding', 'closeBidding');
            Route::post('/{task}/upload-attachment', 'uploadAttachment');


            Route::prefix('assignments')->group(function () {
                Route::post('/{assignment}/cancel', 'cancelAssignment');
            });



        });

        Route::controller(SubTaskController::class)->group(function () {

            Route::prefix('{task}/sub-tasks')->group(function () {
                Route::get('/', 'fetch');
                Route::post('/', 'store');
            });

            Route::prefix('sub-tasks')->group(function () {
                Route::put('/{subTask}', 'update');
                Route::delete('/{subTask}', 'destroy');
                Route::post('/{subTask}/upload-attachment', 'uploadAttachment');
                Route::post('/{subTask}/change-complete', 'changeCompletedStatus');
            });

            Route::prefix('{task}/subtasks')->group(function () {
                Route::get('/', 'fetch');
                Route::post('/', 'store');
            });

            Route::prefix('subtasks')->group(function () {
                Route::put('/{subTask}', 'update');
                Route::delete('/{subTask}', 'destroy');
                Route::post('/{subTask}/toggle', 'changeCompletedStatus');


                Route::prefix('{subTask}/attachments')->group(function () {
                    Route::post('/', 'uploadAttachment');
                });
            });
        });


        Route::prefix('bids')->group(function () {

            Route::controller(BidTaskController::class)->group(function () {
                Route::post('{bidTask}/accept', 'acceptTaskBid');
                Route::post('{bidTask}/reject', 'rejectTaskBid');
                Route::post('{bidTask}/assign', 'directAssignEducator');
                Route::post('{bidTask}/assign', 'cancelAssignEducator');
            });
        });

    });



});
