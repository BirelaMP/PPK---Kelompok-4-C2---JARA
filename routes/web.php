<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerStore']);
});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/projects/{project}', [ProjectController::class, 'legacyShow'])->name('projects.show');
Route::post('/projects/{project}/members', [ProjectMemberController::class, 'legacyStore'])->name('projects.members.store');
Route::delete('/projects/{project}/members/{user}', [ProjectMemberController::class, 'legacyDestroy'])->name('projects.members.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/tasks', [ProjectController::class, 'index'])->name('tasks.index');
    Route::get('/lists', [ProjectController::class, 'index'])->name('lists.index');
    Route::post('/lists', [ProjectController::class, 'store'])->name('lists.store');
    Route::get('/lists/{project}', [ProjectController::class, 'show'])->name('lists.show');
    Route::post('/lists/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/lists/{project}/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
    Route::post('/lists/{project}/members', [ProjectMemberController::class, 'store'])->name('lists.members.store');
    Route::delete('/lists/{project}/members/{user}', [ProjectMemberController::class, 'destroy'])->name('lists.members.destroy');
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});
