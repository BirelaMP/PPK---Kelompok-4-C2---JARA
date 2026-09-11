<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SRS-02: Task Management
|--------------------------------------------------------------------------
| This branch provides routes strictly for Task Management.
| Authentication, user management, and task lists are integrated by the PM.
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/tasks');

Route::middleware('auth')->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
});
