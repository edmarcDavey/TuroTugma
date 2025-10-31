<?php

use Illuminate\Support\Facades\Route;

// Public landing page (no auth required)
Route::get('/', function () {
    return view('landing');
});

// Dashboard placeholder for 'Get Started' (no auth for scaffold)
Route::get('/dashboard', function () {
    return view('dashboard');
});

// Role-specific dashboards (require auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/it', function () {
        return view('dashboard_it');
    })->name('dashboard.it');

    Route::get('/dashboard/scheduler', function () {
        return view('dashboard_scheduler');
    })->name('dashboard.scheduler');
});

// Simple pages for Features and About
Route::get('/features', function () {
    return view('features');
});

Route::get('/about', function () {
    return view('about');
});

// Include authentication routes (login, register, password, etc.)
require __DIR__.'/auth.php';
