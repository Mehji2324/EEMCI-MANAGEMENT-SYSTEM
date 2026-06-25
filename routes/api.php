<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\StudentValidationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\PaymentController;

Route::get('/health', [\App\Http\Controllers\HealthController::class, 'check']);

Route::prefix('v1')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Public Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('throttle:20,1')->group(function () {

        Route::post('/auth/register', [AuthController::class, 'register']);
        Route::post('/auth/login', [AuthController::class, 'login']);
    });

    /*
    |--------------------------------------------------------------------------
    | Protected Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

        // Auth & Profile
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/change-password', [AuthController::class, 'changePassword'])
             ->middleware('throttle:5,1');

        /*
        |--------------------------------------------------------------------------
        | Admin Only Routes
        |--------------------------------------------------------------------------
        */
        Route::middleware('role:admin')->group(function () {
            Route::get('/admin/dashboard/stats', [DashboardController::class, 'stats']);

            // Student Validation Workflow
            Route::get('/admin/students/pending', [StudentValidationController::class, 'pending']);
            Route::post('/admin/students/{student}/validate', [StudentValidationController::class, 'validate']);
            Route::post('/admin/students/{student}/reject', [StudentValidationController::class, 'reject']);

            // Teachers CRUD
            Route::apiResource('teachers', TeacherController::class)->except(['show']);

            // Academic Structure CRUD
            Route::apiResource('filieres', FiliereController::class)->except(['index', 'show']);
            Route::apiResource('classes', ClasseController::class)->except(['index', 'show']);
            Route::apiResource('modules', ModuleController::class)->except(['index', 'show']);

            // Validations
            Route::put('/notes/{note}/validate', [NoteController::class, 'validateNote']);
            Route::put('/absences/{absence}/validate', [AbsenceController::class, 'validateAbsence']);
            Route::put('/payments/{payment}/status', [PaymentController::class, 'updateStatus']);
            
            // Delete Student
            Route::delete('/students/{student}', [StudentController::class, 'destroy']);
        });

        /*
        |--------------------------------------------------------------------------
        | Mixed Access Routes (Admin & Teacher)
        |--------------------------------------------------------------------------
        */
        Route::middleware('role:admin,teacher')->group(function () {
            Route::post('/notes', [NoteController::class, 'store']);
            Route::post('/absences', [AbsenceController::class, 'store']);
        });

        /*
        |--------------------------------------------------------------------------
        | General Access Routes (Role checks inside controllers)
        |--------------------------------------------------------------------------
        */
        // View Academic Structure
        Route::get('/filieres', [FiliereController::class, 'index']);
        Route::get('/filieres/{filiere}', [FiliereController::class, 'show']);
        Route::get('/classes', [ClasseController::class, 'index']);
        Route::get('/classes/{classe}', [ClasseController::class, 'show']);
        Route::get('/modules', [ModuleController::class, 'index']);
        Route::get('/modules/{module}', [ModuleController::class, 'show']);

        // View Users
        Route::get('/students', [StudentController::class, 'index'])->middleware('role:admin,teacher');
        Route::get('/students/{student}', [StudentController::class, 'show']);
        Route::put('/students/{student}', [StudentController::class, 'update'])->middleware('role:admin');
        Route::get('/teachers/{teacher}', [TeacherController::class, 'show']);

        // Notes, Absences & Payments View
        Route::get('/notes', [NoteController::class, 'index']);
        Route::get('/absences', [AbsenceController::class, 'index']);
        Route::get('/absences/{absence}/download', [AbsenceController::class, 'downloadJustification']);
        Route::get('/payments', [PaymentController::class, 'index']);
        Route::post('/payments', [PaymentController::class, 'store'])->middleware('role:admin');
        Route::get('/payments/{payment}', [PaymentController::class, 'show']);
        Route::put('/auth/profile', [StudentController::class, 'updateOwnProfile']);

    });
});
