<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
|
| Routes for version 1 of the API. All routes are prefixed with "api/v1"
| and protected routes use the 'auth:api' middleware.
|
*/

// ------------------------
// Authentication Routes
// ------------------------
Route::name('auth.')
    ->prefix('auth')
    ->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login', [AuthController::class, 'login'])->name('login');

        // Protected auth routes
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('me', [AuthController::class, 'me'])->name('me');
            Route::get('refresh', [AuthController::class, 'refresh'])->name('refresh');
            Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

// ------------------------
// User Routes (Protected)
// ------------------------
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('users/active', [UserController::class, 'active'])->name('users.active');
    Route::get('users/all', [UserController::class, 'all'])->name('users.all');
    Route::apiResource('users', UserController::class)->names('users');

    // ------------------------
    // Rotas de Tarefas
    // ------------------------
    Route::apiResource('tasks', TaskController::class)->names('tasks');
    Route::patch('tasks/{task}/done', [TaskController::class, 'markAsDone'])->name('tasks.done');
});
