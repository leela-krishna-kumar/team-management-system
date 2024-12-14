<?php

use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\RolesPermissionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;

// Home Route
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Auth::routes();

// Dashboard
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Team Management Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('teams', TeamController::class)->except(['create', 'edit', 'show']);
    Route::get('/teams/{team}/tasks', [TaskController::class, 'index'])->name('teams.tasks');
});

// Task Management Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('tasks', TaskController::class)->except(['create', 'edit', 'show']);
    Route::post('/tasks/{task}/assign-users', [TaskController::class, 'assignUsers'])->name('tasks.assign-users');
});

// Role and Permission Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/roles-permissions', [RolesPermissionsController::class, 'index'])->name('roles-permissions.index');
    Route::post('/roles-permissions/assign-role', [RolesPermissionsController::class, 'assignRole'])->name('roles-permissions.assign-role');
    Route::post('/roles-permissions/assign-permission', [RolesPermissionsController::class, 'assignPermission'])->name('roles-permissions.assign-permission');
    Route::post('/roles-permissions/revoke-permission', [RolesPermissionsController::class, 'revokePermission'])->name('roles-permissions.revoke-permission');
});
