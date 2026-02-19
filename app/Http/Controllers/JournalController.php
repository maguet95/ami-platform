<?php

namespace App\Http\Controllers;

use App\Exports\TradeEntriesExport;
use App\Models\TradePair;
use App\Services\TradingStatsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        if (! config('journal.enabled')) {
            abort(404);
        }

        $user = Auth::user();

        // Premium access check
        if (! $user->hasPremiumAccess()) {
            return view('journal.upsell');
        }

        // Stats from all_time summary
        $allTimeSummary = $user->journalSummaries()->where('period_type', 'all_time')->first();

        // Recent summaries
        $weeklySummaries = $user->journalSummaries()
            ->where('period_type', 'weekly')
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
                $tradesQuery->where('pnl', '>', 0);
            } elseif ($request->result === 'losing') {
                $tradesQuery->where('pnl', '<', 0);
            }
        }

        // Filter: date range
        if ($request->filled('from')) {
            $tradesQuery->where('opened_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $tradesQuery->where('opened_at', '<=', $request->to.' 23:59:59');
        }

        $trades = $tradesQuery->paginate(20)->withQueryString();

        // Available pairs for filter dropdown
        $userPairs = TradePair::whereIn('id',
            $user->tradeEntries()->select('trade_pair_id')->distinct()
        )->orderBy('symbol')->pluck('symbol');

        return view('journal.index', compact(
            'allTimeSummary',
            'weeklySummaries',
            'trades',
            'userPairs',
        ));
    }

    public function exportExcel(Request $request)
    {
        if (! config('journal.enabled') || ! config('journal.exports_enabled')) {
            abort(404);
        }

        $user = Auth::user();
        if (! $user->hasPremiumAccess()) {
            abort(403);
        }

        $trades = $this->getFilteredTrades($request);

        return Excel::download(new TradeEntriesExport($trades), 'journal_'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        if (! config('journal.enabled') || ! config('journal.exports_enabled')) {
            abort(404);
        }

        $user = Auth::user();
        if (! $user->hasPremiumAccess()) {
            abort(403);
        }

        $trades = $this->getFilteredTrades($request);
        $stats = TradingStatsService::automatic(Auth::id())->getOverviewMetrics();

        $pdf = Pdf::loadView('exports.trade-entries-pdf', [
            'trades' => $trades,
            'stats' => $stats,
            'user' => $user,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('journal_'.now()->format('Y-m-d').'.pdf');
    }

    private function getFilteredTrades(Request $request)
    {
        $query = Auth::user()->tradeEntries()
            ->with('tradePair')
            ->orderByDesc('opened_at');

        if ($request->filled('pair')) {
            $query->whereHas('tradePair', fn ($q) => $q->where('symbol', $request->pair));
        }
        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }
        if ($request->filled('result')) {
            match ($request->result) {
                'winning' => $query->where('pnl', '>', 0),
                'losing' => $query->where('pnl', '<', 0),
                default => null,
            };
        }
        if ($request->filled('from')) {
            $query->where('opened_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('opened_at', '<=', $request->to.' 23:59:59');
        }

        return $query->limit(5000)->get();
    }
}
