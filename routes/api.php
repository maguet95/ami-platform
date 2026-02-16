<?php

use App\Http\Controllers\Api\JournalApiController;
use App\Http\Controllers\LiveClassController;
use Illuminate\Support\Facades\Route;

// Journal Internal API (authenticated via X-API-Key header)
Route::prefix('internal/journal')->middleware('journal.api')->group(function () {
    Route::get('/health', [JournalApiController::class, 'health']);
    Route::post('/entries', [JournalApiController::class, 'storeEntries'])
        ->middleware('journal.api:write:entries');
    Route::post('/summaries', [JournalApiController::class, 'storeSummaries'])
        ->middleware('journal.api:write:summaries');
});

// Live Class Notifications Trigger (authenticated via X-API-Key header)
Route::get('/internal/trigger-class-notifications', [LiveClassController::class, 'triggerNotifications'])
    ->middleware('journal.api');
