<?php

namespace App\Http\Controllers;

use App\Models\TradeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        if (! config('journal.enabled')) {
            abort(404);
        }

        $user = Auth::user();

        // Premium access check
        if (! $user->hasActiveSubscription()) {
            return view('journal.upsell');
        }

        // Stats from all_time summary
        $allTimeSummary = $user->journalSummaries()->allTime()->first();

        // Recent summaries
        $weeklySummaries = $user->journalSummaries()
            ->forPeriod('weekly')
            ->orderByDesc('period_start')
            ->limit(12)
            ->get();

        // Trades with filters
        $tradesQuery = $user->tradeEntries()
            ->with('tradePair')
            ->orderByDesc('opened_at');

        // Filter: pair
        if ($request->filled('pair')) {
            $tradesQuery->whereHas('tradePair', fn ($q) => $q->where('symbol', $request->pair));
        }

        // Filter: direction
        if ($request->filled('direction')) {
            $tradesQuery->where('direction', $request->direction);
        }

        // Filter: status
        if ($request->filled('status')) {
            $tradesQuery->where('status', $request->status);
        }

        // Filter: result (winning/losing)
        if ($request->filled('result')) {
            if ($request->result === 'winning') {
                $tradesQuery->winning();
            } elseif ($request->result === 'losing') {
                $tradesQuery->losing();
            }
        }

        // Filter: date range
        if ($request->filled('from')) {
            $tradesQuery->where('opened_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $tradesQuery->where('opened_at', '<=', $request->to . ' 23:59:59');
        }

        $trades = $tradesQuery->paginate(20)->withQueryString();

        // Available pairs for filter dropdown
        $userPairs = $user->tradeEntries()
            ->select('trade_pair_id')
            ->distinct()
            ->with('tradePair:id,symbol')
            ->get()
            ->pluck('tradePair.symbol')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('journal.index', compact(
            'allTimeSummary',
            'weeklySummaries',
            'trades',
            'userPairs',
        ));
    }
}
