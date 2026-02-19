<?php

namespace App\Console\Commands;

use App\Models\JournalSummary;
use App\Models\TradeEntry;
use App\Services\TradingStatsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CalculateJournalStats extends Command
{
    protected $signature = 'journal:calculate-stats';

    protected $description = 'Calculate journal summaries (all_time, weekly, monthly) for users with trades';

    public function handle(): int
    {
        $userIds = TradeEntry::select('user_id')
            ->distinct()
            ->pluck('user_id');

        if ($userIds->isEmpty()) {
            $this->info('No users with trades found.');
            return 0;
        }

        $this->info("Calculating stats for {$userIds->count()} user(s)...");
        $processed = 0;

        foreach ($userIds as $userId) {
            try {
                $this->calculateForUser($userId);
                $processed++;
            } catch (\Exception $e) {
                $this->error("User {$userId}: {$e->getMessage()}");
                Log::error('journal:calculate-stats failed', [
                    'user_id' => $userId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Done. Processed {$processed}/{$userIds->count()} users.");

        return 0;
    }

    private function calculateForUser(int $userId): void
    {
        $stats = TradingStatsService::automatic($userId);
        $overview = $stats->getOverviewMetrics();

        if ($overview['total_trades'] === 0) {
            return;
        }

        $now = now();

        // All-time summary
        $trades = TradeEntry::where('user_id', $userId)->closed()->orderBy('opened_at')->get();
        $firstTrade = $trades->first();
        $lastTrade = $trades->last();

        JournalSummary::updateOrCreate(
            [
                'user_id' => $userId,
                'period_type' => 'all_time',
                'period_start' => $firstTrade->opened_at->toDateString(),
            ],
            [
                'period_end' => $lastTrade->opened_at->toDateString(),
                'total_trades' => $overview['total_trades'],
                'winning_trades' => $trades->where('pnl', '>', 0)->count(),
                'losing_trades' => $trades->where('pnl', '<', 0)->count(),
                'win_rate' => $overview['win_rate'],
                'total_pnl' => $overview['total_pnl'],
                'max_drawdown' => $overview['max_drawdown'],
                'best_trade_pnl' => $overview['best_trade'],
                'worst_trade_pnl' => $overview['worst_trade'],
                'avg_trade_duration' => (int) ($trades->avg('duration_seconds') ?? 0),
                'profit_factor' => $overview['profit_factor'],
                'calculated_at' => $now,
            ]
        );

        // Weekly summaries (last 12 weeks)
        $weekStart = Carbon::now()->subWeeks(12)->startOfWeek();
        while ($weekStart->lt(now())) {
            $weekEnd = $weekStart->copy()->endOfWeek();
            $weekTrades = $trades->filter(
                fn ($t) => $t->opened_at->between($weekStart, $weekEnd)
            );

            if ($weekTrades->isNotEmpty()) {
                $winners = $weekTrades->where('pnl', '>', 0);
                $losers = $weekTrades->where('pnl', '<', 0);
                $grossProfit = $winners->sum('pnl');
                $grossLoss = abs($losers->sum('pnl'));

                JournalSummary::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'period_type' => 'weekly',
                        'period_start' => $weekStart->toDateString(),
                    ],
                    [
                        'period_end' => $weekEnd->toDateString(),
                        'total_trades' => $weekTrades->count(),
                        'winning_trades' => $winners->count(),
                        'losing_trades' => $losers->count(),
                        'win_rate' => round($winners->count() / $weekTrades->count() * 100, 2),
                        'total_pnl' => $weekTrades->sum('pnl'),
                        'max_drawdown' => 0,
                        'best_trade_pnl' => $weekTrades->max('pnl') ?? 0,
                        'worst_trade_pnl' => $weekTrades->min('pnl') ?? 0,
                        'avg_trade_duration' => (int) ($weekTrades->avg('duration_seconds') ?? 0),
                        'profit_factor' => $grossLoss > 0 ? round($grossProfit / $grossLoss, 4) : 0,
                        'calculated_at' => $now,
                    ]
                );
            }

            $weekStart->addWeek();
        }

        // Monthly summaries (last 6 months)
        $monthStart = Carbon::now()->subMonths(6)->startOfMonth();
        while ($monthStart->lt(now())) {
            $monthEnd = $monthStart->copy()->endOfMonth();
            $monthTrades = $trades->filter(
                fn ($t) => $t->opened_at->between($monthStart, $monthEnd)
            );

            if ($monthTrades->isNotEmpty()) {
                $winners = $monthTrades->where('pnl', '>', 0);
                $losers = $monthTrades->where('pnl', '<', 0);
                $grossProfit = $winners->sum('pnl');
                $grossLoss = abs($losers->sum('pnl'));

                JournalSummary::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'period_type' => 'monthly',
                        'period_start' => $monthStart->toDateString(),
                    ],
                    [
                        'period_end' => $monthEnd->toDateString(),
                        'total_trades' => $monthTrades->count(),
                        'winning_trades' => $winners->count(),
                        'losing_trades' => $losers->count(),
                        'win_rate' => round($winners->count() / $monthTrades->count() * 100, 2),
                        'total_pnl' => $monthTrades->sum('pnl'),
                        'max_drawdown' => 0,
                        'best_trade_pnl' => $monthTrades->max('pnl') ?? 0,
                        'worst_trade_pnl' => $monthTrades->min('pnl') ?? 0,
                        'avg_trade_duration' => (int) ($monthTrades->avg('duration_seconds') ?? 0),
                        'profit_factor' => $grossLoss > 0 ? round($grossProfit / $grossLoss, 4) : 0,
                        'calculated_at' => $now,
                    ]
                );
            }

            $monthStart->addMonth();
        }

        // Invalidate cached stats
        $stats->invalidateCache();
    }
}
