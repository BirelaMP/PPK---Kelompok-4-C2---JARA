<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('projects', ProjectController::class);
});

// Authentication routes akan diintegrasikan dari branch User Management.