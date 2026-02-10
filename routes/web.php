<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
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

// Pricing (public)
Route::get('/planes', [SubscriptionController::class, 'index'])->name('pricing');

// Stripe Webhook
Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('stripe.webhook');

// Public Profiles & Ranking
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');
Route::get('/trader/{user:username}', [PublicProfileController::class, 'show'])->name('profile.public');

// Authenticated Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/perfil/publico', [ProfileController::class, 'editPublic'])->name('profile.edit-public');
    Route::patch('/perfil/publico', [ProfileController::class, 'updatePublic'])->name('profile.update-public');

    // Achievements
    Route::get('/logros', [AchievementController::class, 'index'])->name('achievements');

    // Subscription Routes
    Route::post('/suscripcion/{plan:slug}/checkout', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::get('/suscripcion/exito', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/suscripcion/portal', [SubscriptionController::class, 'portal'])->name('subscription.portal');

    // Student Routes
    Route::get('/mis-cursos', [StudentCourseController::class, 'index'])->name('student.courses');
    Route::post('/cursos/{course}/inscribir', [StudentCourseController::class, 'enroll'])->name('student.enroll');
    Route::get('/aprender/{course}', [StudentCourseController::class, 'show'])->name('student.course');
    Route::get('/aprender/{course}/leccion/{lesson}', [StudentCourseController::class, 'lesson'])->name('student.lesson');
    Route::post('/aprender/{course}/leccion/{lesson}/completar', [StudentCourseController::class, 'completeLesson'])->name('student.lesson.complete');
});

require __DIR__.'/auth.php';
