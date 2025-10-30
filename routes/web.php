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

// Simple pages for Features and About
Route::get('/features', function () {
    return view('features');
});

Route::get('/about', function () {
    return view('about');
});

// Include authentication routes (login, register, password, etc.)
require __DIR__.'/auth.php';
