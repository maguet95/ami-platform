<?php

namespace App\Http\Controllers;

use App\Exports\ManualTradesExport;
use App\Models\ManualTrade;
use App\Services\TradingStatsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ManualJournalExportController extends Controller
{
    public function exportExcel(Request $request)
    {
        if (! config('journal.exports_enabled')) {
            abort(404);
        }

        $trades = $this->getFilteredTrades($request);
        $filename = 'bitacora_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new ManualTradesExport($trades), $filename);
    }

    public function exportPdf(Request $request)
    {
        if (! config('journal.exports_enabled')) {
            abort(404);
        }

        $trades = $this->getFilteredTrades($request);
        $stats = TradingStatsService::manual(Auth::id())->getOverviewMetrics();

        $pdf = Pdf::loadView('exports.manual-trades-pdf', [
            'trades' => $trades,
            'stats' => $stats,
            'user' => Auth::user(),
            'generatedAt' => now()->format('d/m/Y H:i'),
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('bitacora_' . now()->format('Y-m-d') . '.pdf');
    }

    private function getFilteredTrades(Request $request)
    {
        $query = ManualTrade::where('user_id', Auth::id())
            ->with('tradePair')
            ->orderByDesc('trade_date');

        if ($request->filled('pair')) {
            $query->whereHas('tradePair', fn ($q) => $q->where('id', $request->pair));
        }
        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('result')) {
            match ($request->result) {
                'winning' => $query->winning(),
                'losing' => $query->losing(),
                'breakeven' => $query->breakeven(),
                default => null,
            };
        }
        if ($request->filled('from')) {
            $query->where('trade_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('trade_date', '<=', $request->to);
        }

        return $query->get();
    }
}
