<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - JARA (Job Activity & Responsibility Assistant)
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (dynamic Admin / User view)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Task Lists & Collaboration
    Route::resource('task-lists', TaskListController::class)->parameters([
        'task-lists' => 'taskList',
    ]);
    Route::get('/task-lists/{taskList}/members', [TaskListController::class, 'members'])->name('task-lists.members');
    Route::post('/task-lists/{taskList}/members', [TaskListController::class, 'addMember'])->name('task-lists.members.add');
    Route::delete('/task-lists/{taskList}/members/{user}', [TaskListController::class, 'removeMember'])->name('task-lists.members.remove');

    // Tasks Management
    Route::resource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');

    // Admin Only User Management Routes (Protected by role:admin middleware)
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('users', AdminUserController::class);
    });
});
