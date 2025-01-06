<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => ['accept-json']], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], function () {
        Route::group(['prefix' => 'subjects'], function () {
            Route::get('/', [SubjectController::class, 'getAllSubjects']);
            Route::post('/', [SubjectController::class, 'createSubject']);
            Route::put('/{subjectId}', [SubjectController::class, 'updateSubject']);
        });

        Route::group(['prefix' => 'grades'], function () {
            Route::get('/', [GradeController::class, 'getAllGrades']);
            Route::post('/', [GradeController::class, 'createGrade']);
            Route::put('/{gradeId}', [GradeController::class, 'updateGrade']);
        });

        Route::group(['prefix' => 'employees'], function () {
            Route::get('/', [EmployeeController::class, 'getAllEmployees']);
            Route::post('/', [EmployeeController::class, 'createEmployee']);
            // Route::put('/{gradeId}', [EmployeeController::class, 'updateGrade']);
        });
    });

});
