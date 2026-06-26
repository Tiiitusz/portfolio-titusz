<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortfolioController;

Route::get('/', [PortfolioController::class, 'index']);
Route::get('/project/{id}', [PortfolioController::class, 'show'])->name('project.show');

Route::middleware('guest')->group(function () {
	Route::get('/login', [AuthController::class, 'login'])->name('login');
	Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
	Route::get('/admin', [AuthController::class, 'dashboard'])->name('admin.dashboard');
	Route::get('/admin/projects/create', [AuthController::class, 'createProject'])->name('admin.projects.create');
	Route::post('/admin/projects', [AuthController::class, 'storeProject'])->name('admin.projects.store');
	Route::post('/admin/projects/{project}/images', [AuthController::class, 'uploadProjectImages'])->name('admin.projects.images.store');
	Route::get('/admin/projects/{project}/edit', [AuthController::class, 'editProject'])->name('admin.projects.edit');
	Route::put('/admin/projects/{project}', [AuthController::class, 'updateProject'])->name('admin.projects.update');
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});