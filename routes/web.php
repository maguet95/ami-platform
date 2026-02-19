<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\BrokerConnectionController;
use App\Http\Controllers\ManualJournalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\ManualJournalExportController;
use App\Http\Controllers\LiveClassController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TradingStatsController;
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
Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])
    ->middleware('throttle:webhooks')
    ->name('stripe.webhook');

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

    // Internal pages (within platform layout)
    Route::get('/cursos-catalogo', [PlatformController::class, 'coursesCatalog'])->name('platform.courses');
    Route::get('/ranking-interno', [PlatformController::class, 'ranking'])->name('platform.ranking');

    // Achievements
    Route::get('/logros', [AchievementController::class, 'index'])->name('achievements');

    // Trading Journal (premium)
    Route::get('/journal', [JournalController::class, 'index'])->name('journal');
    Route::get('/journal/estadisticas', [TradingStatsController::class, 'automaticStats'])->name('journal.stats');
    Route::get('/journal/exportar/excel', [JournalController::class, 'exportExcel'])->name('journal.export.excel');
    Route::get('/journal/exportar/pdf', [JournalController::class, 'exportPdf'])->name('journal.export.pdf');

    // Journal Broker Connections
    Route::prefix('journal/conexiones')->name('journal.connections')->group(function () {
        Route::get('/', [BrokerConnectionController::class, 'index']);
        Route::post('/', [BrokerConnectionController::class, 'store'])->name('.store');
        Route::delete('/{connection}', [BrokerConnectionController::class, 'destroy'])->name('.destroy');
        Route::patch('/{connection}/sync', [BrokerConnectionController::class, 'toggleSync'])->name('.toggle-sync');
        Route::post('/csv', [BrokerConnectionController::class, 'uploadCsv'])->name('.upload-csv');
    });

    // Subscription Routes
    Route::post('/suscripcion/{plan:slug}/checkout', [SubscriptionController::class, 'checkout'])
        ->middleware('throttle:checkout')
        ->name('subscription.checkout');
    Route::get('/suscripcion/exito', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/suscripcion/portal', [SubscriptionController::class, 'portal'])->name('subscription.portal');

    // Manual Journal (Bitacora) — free for all authenticated users
    Route::prefix('bitacora')->name('bitacora.')->group(function () {
        Route::get('/', [ManualJournalController::class, 'index'])->name('index');
        Route::get('/estadisticas', [TradingStatsController::class, 'manualStats'])->name('stats');
        Route::get('/exportar/excel', [ManualJournalExportController::class, 'exportExcel'])->name('export.excel');
        Route::get('/exportar/pdf', [ManualJournalExportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/crear', [ManualJournalController::class, 'create'])->name('create');
        Route::post('/', [ManualJournalController::class, 'store'])->name('store');
        Route::get('/{trade}', [ManualJournalController::class, 'show'])->name('show');
        Route::get('/{trade}/editar', [ManualJournalController::class, 'edit'])->name('edit');
        Route::put('/{trade}', [ManualJournalController::class, 'update'])->name('update');
        Route::delete('/{trade}', [ManualJournalController::class, 'destroy'])->name('destroy');
        Route::post('/{trade}/duplicar', [ManualJournalController::class, 'duplicate'])->name('duplicate');
        Route::delete('/imagen/{image}', [ManualJournalController::class, 'destroyImage'])->name('image.destroy');
    });

    // Live Classes (Calendar)
    Route::get('/calendario', [LiveClassController::class, 'calendar'])->name('live-classes.calendar');
    Route::get('/clase/{liveClass}', [LiveClassController::class, 'show'])->name('live-classes.show');
    Route::get('/clase/{liveClass}/unirse', [LiveClassController::class, 'join'])->name('live-classes.join');

    // Student Routes
    Route::get('/mis-cursos', [StudentCourseController::class, 'index'])->name('student.courses');
    Route::post('/cursos/{course}/inscribir', [StudentCourseController::class, 'enroll'])->name('student.enroll');
    Route::get('/aprender/{course}', [StudentCourseController::class, 'show'])->name('student.course');
    Route::get('/aprender/{course}/leccion/{lesson}', [StudentCourseController::class, 'lesson'])->name('student.lesson');
    Route::post('/aprender/{course}/leccion/{lesson}/completar', [StudentCourseController::class, 'completeLesson'])->name('student.lesson.complete');
});

require __DIR__.'/auth.php';
