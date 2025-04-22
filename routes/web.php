<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Home');
});

Route::middleware('guest')->group(function () {
    Route::get('signup', [AuthController::class, 'showRegistrationForm'])->name('signup');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('signin', [AuthController::class, 'showLoginForm'])->name('signin');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
