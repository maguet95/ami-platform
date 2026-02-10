<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentCourseController;
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

// Authenticated Routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Student Routes
    Route::get('/mis-cursos', [StudentCourseController::class, 'index'])->name('student.courses');
    Route::post('/cursos/{course}/inscribir', [StudentCourseController::class, 'enroll'])->name('student.enroll');
    Route::get('/aprender/{course}', [StudentCourseController::class, 'show'])->name('student.course');
    Route::get('/aprender/{course}/leccion/{lesson}', [StudentCourseController::class, 'lesson'])->name('student.lesson');
    Route::post('/aprender/{course}/leccion/{lesson}/completar', [StudentCourseController::class, 'completeLesson'])->name('student.lesson.complete');
});

require __DIR__.'/auth.php';
