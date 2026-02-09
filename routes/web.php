<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Public Pages
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/nosotros', 'about')->name('about');
    Route::get('/metodologia', 'methodology')->name('methodology');
    Route::get('/cursos', 'courses')->name('courses');
    Route::get('/contacto', 'contact')->name('contact');
    Route::get('/terminos', 'terms')->name('terms');
    Route::get('/privacidad', 'privacy')->name('privacy');
});

// Auth (placeholder routes until Phase 2)
Route::get('/login', fn () => redirect()->route('home'))->name('login');
Route::get('/registro', fn () => redirect()->route('home'))->name('register');
