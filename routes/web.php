<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::resource('tasks', TaskController::class)->except(['show']);

    Route::get('/tenants/change/{tenantId}', [TenantController::class, 'changeTenant'])->name('tenants.change');

    Route::resource('users', UserController::class)->only(['index', 'store'])->middleware('can:manage_users');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('accept-invitation/{token}', [UserController::class, 'acceptInvitation'])->name('invitation.accept');

require __DIR__.'/auth.php';
