<?php

use App\Application\Educator\Controllers\Api\AssignmentController;
use App\Application\Educator\Controllers\Api\Auth\AuthController;
use App\Application\Educator\Controllers\Api\Auth\PasswordResetController;
use App\Application\Educator\Controllers\Api\BidController;
use App\Application\Educator\Controllers\Api\DashboardController;
use App\Application\Educator\Controllers\Api\FileController;
use App\Application\Educator\Controllers\Api\OpportunityController;
use App\Application\Educator\Controllers\Api\Profile\AcademicMajorController;
use App\Application\Educator\Controllers\Api\Profile\AchievementController;
use App\Application\Educator\Controllers\Api\Profile\BankInfoController;
use App\Application\Educator\Controllers\Api\Profile\EducatorProfileController;
use App\Application\Educator\Controllers\Api\Profile\LanguageController;
use App\Application\Educator\Controllers\Api\Profile\ServiceController;
use App\Application\Educator\Controllers\Api\Profile\SkillController;
use App\Application\Educator\Controllers\Api\ProjectController;
use App\Application\Educator\Controllers\Api\Task\SubTaskController;
use App\Application\Educator\Controllers\Api\Task\TaskController;
use Illuminate\Support\Facades\Route;


// Admin-panel endpoints (managing educator accounts). Previously reachable with
// no token; auth:admin-api is the Sanctum guard that admin login issues.
Route::group(['prefix' => 'v1/admin/','middleware' => 'auth:admin-api'],function (){
    require __DIR__ . '/Modules/Admin/api.php';
});



Route::group(['prefix' => 'v1/educator'],function () {

    Route::prefix('auth')->group(function () {
        Route::controller(AuthController::class)->group(function() {
            Route::post('/login', 'login');
            Route::post('/register', 'register');

            // Email verification flow: { email, code } on both endpoints.
            Route::post('/verify-email-code', 'verifyEmailCode');
            Route::post('/resend-verification-code', 'resendOtp');
        });

        Route::controller(PasswordResetController::class)->group(function() {
            Route::post('/forgot-password', 'forgotPassword');
            Route::post('/verify-reset-code', 'verifyResetCode');
            Route::post('/resend-reset-code', 'resendResetCode');
            Route::post('/reset-password', 'resetPassword');
        });
    });

    Route::group(['middleware' => 'auth:educator-api'],function () {

        Route::prefix('dashboard')->controller(DashboardController::class)->group(function() {
            Route::get('/index', 'index');
        });

        Route::prefix('opportunities')->controller(OpportunityController::class)->group(function() {
            Route::get('/', 'index');
            Route::get('/{project}', 'show');
        });

        Route::prefix('projects')->controller(ProjectController::class)->group(function() {
            Route::get('/', 'myProjects');
            Route::get('/{project}', 'show');
        });

        Route::prefix('bids')->controller(BidController::class)->group(function() {
            Route::get('/', 'index');
            Route::get('/{bid}', 'show');
            Route::post('/', 'store');
            Route::put('/{bid}', 'update');
        });

        Route::prefix('dashboard')->controller(DashboardController::class)->group(function() {
            Route::get('/stats', 'stats');
            Route::get('/statsResponse', 'stats');
        });

        Route::prefix('notifications')->controller(DashboardController::class)->group(function() {
            Route::get('/', 'notifications');
            Route::post('/mark-all-read', 'markAllNotificationsRead');
            Route::put('/{notification}/read', 'markNotificationRead');
        });


        Route::prefix('assignments')->controller(AssignmentController::class)->group(function() {
            Route::get('/', 'fetch');
            Route::post('/{assignment}/accept', 'acceptInvitation');
            Route::post('/{assignment}/decline', 'declineInvitation');
        });

        Route::prefix('tasks')->group(function () {

            Route::controller(TaskController::class)->group(function (){
                Route::get('/', 'fetch');
                Route::get('/{task}','show');
                Route::post('/{task}/upload-attachment', 'uploadAttachment');
                Route::post('/{task}/complete', 'complete');
                Route::delete('/{task}/deliverables/{deliverableId}', 'destroyDeliverable');

                Route::prefix('assignments')->group(function () {
                    Route::post('/{assignment}/cancel', 'cancelAssignment');
                });
            });

            // Route::controller(SubTaskController::class)->group(function () {

            //     Route::prefix('{task}/subtasks')->group(function () {
            //         Route::get('/', 'fetch');
            //         Route::post('/', 'store');
            //     });

            //     Route::prefix('subtasks')->group(function () {
            //         Route::put('/{subTask}', 'update');
            //         Route::delete('/{subTask}', 'destroy');
            //         Route::post('/{subTask}/toggle', 'toggleComplete');

            //         Route::prefix('{subTask}/attachments')->group(function () {
            //             Route::post('/', 'uploadAttachment');
            //         });
            //     });
            // });

            Route::controller(SubTaskController::class)->group(function () {

                Route::prefix('{task}/subtasks')->group(function () {
                    Route::get('/', 'fetch');
                    Route::post('/', 'store');
                });

                // Route::prefix('sub-tasks')->group(function () {
                //     Route::put('/{subTask}', 'update');
                //     Route::delete('/{subTask}', 'destroy');
                //     Route::post('/{subTask}/upload-attachment', 'uploadAttachment');
                //     Route::post('/{subTask}/change-complete', 'changeCompletedStatus');
                // });

                // Route::prefix('{task}/subtasks')->group(function () {
                //     Route::get('/', 'fetch');
                //     Route::post('/', 'store');

                //     Route::put('/{subTask}', 'update');
                //     Route::delete('/{subTask}', 'destroy');
                //     Route::post('/{subTask}/toggle', 'changeCompletedStatus');
                // });

                Route::prefix('subtasks')->group(function () {
                    Route::put('/{subTask}', 'update');
                    Route::delete('/{subTask}', 'destroy');
                    Route::post('/{subTask}/toggle', 'toggleComplete');


                    Route::prefix('{subTask}/attachments')->group(function () {
                        Route::post('/', 'uploadAttachment');
                    });
                });
            });


        });



        // Profile
        Route::prefix('profile')->group(function () {

            Route::controller(EducatorProfileController::class)->group(function (){
                Route::get('/', 'show');
                Route::patch('/','update');
                Route::post('/basic-info','updateBasicInfo');
                Route::put('/basic-info','updateBasicInfo');
                Route::post('/biography','updateBiography');
            });

            Route::prefix('academic-majors')->controller(AcademicMajorController::class)->group(function () {
                Route::get('/','fetch');
                Route::post('/','store');
                Route::put('/{educatorMajor}', 'update');
                Route::delete('/{educatorMajor}', 'destroy');

            });

            Route::prefix('achievements')->controller(AchievementController::class)->group(function () {
                Route::get('/','fetch');
                Route::post('/','store');
                Route::put('/{achievement}','update');
                Route::delete('/{achievement}','destroy');
            });

            Route::prefix('languages')->controller(LanguageController::class)->group(function () {
                Route::get('/','fetch');
                Route::post('/','store');
                Route::put('/{language}','update');
                Route::delete('/{language}','destroy');
            });

            Route::prefix('services')->controller(ServiceController::class)->group(function () {
                Route::get('/','fetch');
                Route::post('/','store');
                Route::put('/{service}','update');
                Route::delete('/{service}','destroy');
            });

            Route::prefix('skills')->controller(SkillController::class)->group(function () {
                Route::get('/','fetch');
                Route::post('/','store');
                Route::put('/{skill}','update');
                Route::delete('/{skill}','destroy');
            });

            Route::prefix('bank-info')->controller(BankInfoController::class)->group(function () {
                Route::get('/','fetch');
                Route::post('/','createOrUpdate');
                Route::delete('/','destroy');
            });

        });

    });
});
