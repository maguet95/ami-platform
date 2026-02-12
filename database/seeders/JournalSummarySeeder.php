<?php

namespace Database\Seeders;

use App\Models\JournalSummary;
use App\Models\TradeEntry;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class JournalSummarySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'enmajose95+admin@gmail.com')->first();
        if (! $admin) {
            $this->command->warn('Admin user not found. Skipping JournalSummarySeeder.');
            return;
        }

        $trades = TradeEntry::where('user_id', $admin->id)->closed()->orderBy('opened_at')->get();

        if ($trades->isEmpty()) {
            $this->command->warn('No trade entries found. Run TradeEntrySeeder first.');
            return;
        }

        $now = Carbon::now();

        // Weekly summaries (last 12 weeks)
        for ($w = 12; $w >= 0; $w--) {
            $weekStart = $now->copy()->subWeeks($w)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            $weekTrades = $trades->filter(fn ($t) =>
                $t->opened_at->gte($weekStart) && $t->opened_at->lte($weekEnd)
            );

            if ($weekTrades->isEmpty()) continue;

            $this->createSummary($admin->id, 'weekly', $weekStart, $weekEnd, $weekTrades);
        }

        // Monthly summaries (last 3 months)
        for ($m = 3; $m >= 0; $m--) {
            $monthStart = $now->copy()->subMonths($m)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();

            $monthTrades = $trades->filter(fn ($t) =>
                $t->opened_at->gte($monthStart) && $t->opened_at->lte($monthEnd)
            );

            if ($monthTrades->isEmpty()) continue;

            $this->createSummary($admin->id, 'monthly', $monthStart, $monthEnd, $monthTrades);
        }

        // All-time summary
        $this->createSummary(
            $admin->id,
            'all_time',
            $trades->first()->opened_at,
            $trades->last()->opened_at,
            $trades
        );

        $this->command->info('Created journal summaries for admin user.');
    }

    private function createSummary(int $userId, string $periodType, Carbon $start, Carbon $end, $trades): void
    {
        $total = $trades->count();
        $winners = $trades->where('pnl', '>', 0);
        $losers = $trades->where('pnl', '<', 0);

        $totalPnl = $trades->sum('pnl');
        $grossProfit = $winners->sum('pnl');
        $grossLoss = abs($losers->sum('pnl'));
        $profitFactor = $grossLoss > 0 ? round($grossProfit / $grossLoss, 4) : ($grossProfit > 0 ? 99.0 : 0);

        // Max drawdown calculation
        $cumPnl = 0;
        $peak = 0;
        $maxDrawdown = 0;
        foreach ($trades->sortBy('opened_at') as $t) {
            $cumPnl += $t->pnl;
            $peak = max($peak, $cumPnl);
            $dd = $peak > 0 ? (($peak - $cumPnl) / $peak) * 100 : 0;
            $maxDrawdown = max($maxDrawdown, $dd);
        }

        $avgDuration = $trades->avg('duration_seconds') ?? 0;

        JournalSummary::updateOrCreate(
            [
                'user_id' => $userId,
                'period_type' => $periodType,
                'period_start' => $start->toDateString(),
            ],
            [
                'period_end' => $end->toDateString(),
                'total_trades' => $total,
                'winning_trades' => $winners->count(),
                'losing_trades' => $losers->count(),
                'win_rate' => $total > 0 ? round(($winners->count() / $total) * 100, 2) : 0,
                'total_pnl' => round($totalPnl, 2),
                'max_drawdown' => round($maxDrawdown, 4),
                'best_trade_pnl' => $trades->max('pnl') ?? 0,
                'worst_trade_pnl' => $trades->min('pnl') ?? 0,
                'avg_trade_duration' => round($avgDuration),
                'profit_factor' => $profitFactor,
                'calculated_at' => now(),
            ]
        );
    }
}
