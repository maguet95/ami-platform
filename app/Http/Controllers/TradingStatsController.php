<?php

namespace App\Http\Controllers;

use App\Services\TradingStatsService;
use Illuminate\Support\Facades\Auth;

class TradingStatsController extends Controller
{
    public function manualStats()
    {
        if (! config('journal.manual_enabled') || ! config('journal.stats_enabled')) {
            abort(404);
        }

        $stats = TradingStatsService::manual(Auth::id())->getAllStats();

        return view('stats.trading-stats', [
            'stats' => $stats,
            'journalType' => 'manual',
            'title' => 'Estadisticas — Bitacora Manual',
            'backRoute' => route('bitacora.index'),
            'backLabel' => 'Bitacora',
        ]);
    }

    public function automaticStats()
    {
        if (! config('journal.enabled') || ! config('journal.stats_enabled')) {
            abort(404);
        }

        $user = Auth::user();

        if (! $user->hasActiveSubscription()) {
            return view('journal.upsell');
        }

        $stats = TradingStatsService::automatic(Auth::id())->getAllStats();

        return view('stats.trading-stats', [
            'stats' => $stats,
            'journalType' => 'automatic',
            'title' => 'Estadisticas — Journal Automatico',
            'backRoute' => route('journal'),
            'backLabel' => 'Journal',
        ]);
    }
}
