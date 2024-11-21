<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
// Fortify
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ProfileInformationController;
use Laravel\Fortify\Http\Controllers\PasswordController;
//Custom
use App\Http\Controllers\ActivityHistoryController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\EnumerationController;
use App\Http\Controllers\LoginHistoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Authentication...
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('login');

$limiter = config('fortify.limiters.login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(array_filter([
        'guest:' . config('fortify.guard'),
        $limiter ? 'throttle:' . $limiter : null,
    ]));

Route::middleware([IsAdmin::class])->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('/user-guide', 'admin.user_guide')->name('user-guide');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
        ->name('logout');

    // Profile Information...
    Route::view('/profile/edit', 'admin.auth.edit')->name('my-profile');
    Route::put('/user/profile-information', [ProfileInformationController::class, 'update'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
        ->name('user-profile-information.update');

    // Passwords...
    Route::view('/profile/password', 'admin.auth.password')->name('change-password');
    Route::put('/user/password', [PasswordController::class, 'update'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
        ->name('user-password.update');

    // Access Control
    Route::resources([
        '/permissions' => PermissionController::class,
        '/roles' => RoleController::class,
        '/users' => UserController::class,
    ]);
    Route::get('/login-history', [LoginHistoryController::class, 'index'])->name('login-history');
    Route::post('/login-history/clear', [LoginHistoryController::class, 'clear'])->name('clear-login-history');
    Route::get('/activity-history', [ActivityHistoryController::class, 'index'])->name('activity-history');
    Route::post('/activity-history/clear', [ActivityHistoryController::class, 'clear'])->name('clear-activity-history');

    // Domains & Enumerations
    Route::resources([
        '/domains' => DomainController::class,
        '/enumerations' => EnumerationController::class,
    ]);
});
