<?php

namespace App\Services;

use App\Models\ManualTrade;
use App\Models\TradeEntry;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class TradingStatsService
{
    private int $userId;
    private string $journalType; // 'manual' or 'automatic'

    public function __construct(int $userId, string $journalType = 'manual')
    {
        $this->userId = $userId;
        $this->journalType = $journalType;
    }

    public static function manual(int $userId): self
    {
        return new self($userId, 'manual');
    }

    public static function automatic(int $userId): self
    {
        return new self($userId, 'automatic');
    }

    public function getAllStats(): array
    {
        $cacheKey = "trading_stats:{$this->journalType}:{$this->userId}";

        return Cache::remember($cacheKey, 300, function () {
            $trades = $this->getTrades();

            return [
                'overview' => $this->getOverviewMetrics($trades),
                'equity_curve' => $this->getEquityCurve($trades),
                'daily_pnl' => $this->getDailyPnl($trades),
                'weekly_pnl' => $this->getWeeklyPnl($trades),
                'pair_distribution' => $this->getPairDistribution($trades),
                'direction_stats' => $this->getDirectionStats($trades),
                'session_stats' => $this->getSessionStats($trades),
                'timeframe_stats' => $this->getTimeframeStats($trades),
                'streaks' => $this->getStreaks($trades),
                'monthly_returns' => $this->getMonthlyReturns($trades),
            ];
        });
    }

    public function invalidateCache(): void
    {
        Cache::forget("trading_stats:{$this->journalType}:{$this->userId}");
    }

    private function getTrades(): Collection
    {
        if ($this->journalType === 'manual') {
            return ManualTrade::where('user_id', $this->userId)
                ->closed()
                ->whereNotNull('pnl')
                ->with('tradePair')
                ->orderBy('trade_date')
                ->get();
        }

        return TradeEntry::where('user_id', $this->userId)
            ->closed()
            ->whereNotNull('pnl')
            ->with('tradePair')
            ->orderBy('opened_at')
            ->get();
    }

    public function getOverviewMetrics(?Collection $trades = null): array
    {
        $trades = $trades ?? $this->getTrades();

        if ($trades->isEmpty()) {
            return $this->emptyOverview();
        }

        $total = $trades->count();
        $winners = $trades->where('pnl', '>', 0);
        $losers = $trades->where('pnl', '<', 0);

        $totalPnl = $trades->sum('pnl');
        $grossProfit = $winners->sum('pnl');
        $grossLoss = abs($losers->sum('pnl'));
        $profitFactor = $grossLoss > 0 ? round($grossProfit / $grossLoss, 2) : ($grossProfit > 0 ? 99.0 : 0);

        // Max drawdown
        $cumPnl = 0;
        $peak = 0;
        $maxDrawdown = 0;
        foreach ($trades as $t) {
            $cumPnl += $t->pnl;
            $peak = max($peak, $cumPnl);
            $dd = $peak > 0 ? $peak - $cumPnl : 0;
            $maxDrawdown = max($maxDrawdown, $dd);
        }

        // Expectancy
        $avgWin = $winners->count() > 0 ? $winners->avg('pnl') : 0;
        $avgLoss = $losers->count() > 0 ? abs($losers->avg('pnl')) : 0;
        $winRate = $total > 0 ? $winners->count() / $total : 0;
        $lossRate = 1 - $winRate;
        $expectancy = ($avgWin * $winRate) - ($avgLoss * $lossRate);

        // Profitable days
        $dateField = $this->journalType === 'manual' ? 'trade_date' : 'opened_at';
        $dailyPnl = $trades->groupBy(fn ($t) => Carbon::parse($t->$dateField)->toDateString());
        $profitableDays = $dailyPnl->filter(fn ($group) => $group->sum('pnl') > 0)->count();

        // Avg RR
        $avgRr = $this->journalType === 'manual'
            ? $trades->whereNotNull('risk_reward_actual')->avg('risk_reward_actual')
            : null;

        // Streaks
        $streakData = $this->calculateStreaks($trades);

        return [
            'total_trades' => $total,
            'win_rate' => round($winRate * 100, 1),
            'total_pnl' => round($totalPnl, 2),
            'profit_factor' => $profitFactor,
            'max_drawdown' => round($maxDrawdown, 2),
            'expectancy' => round($expectancy, 2),
            'best_trade' => round($trades->max('pnl'), 2),
            'worst_trade' => round($trades->min('pnl'), 2),
            'avg_rr' => $avgRr ? round($avgRr, 2) : null,
            'profitable_days' => $profitableDays,
            'total_days' => $dailyPnl->count(),
            'current_streak' => $streakData['current'],
            'best_streak' => $streakData['best'],
            'avg_win' => round($avgWin, 2),
            'avg_loss' => round($avgLoss, 2),
        ];
    }

    public function getEquityCurve(?Collection $trades = null): array
    {
        $trades = $trades ?? $this->getTrades();
        $dateField = $this->journalType === 'manual' ? 'trade_date' : 'opened_at';

        $cumPnl = 0;
        $curve = [];
        foreach ($trades as $t) {
            $cumPnl += $t->pnl;
            $curve[] = [
                'date' => Carbon::parse($t->$dateField)->toDateString(),
                'pnl' => round($cumPnl, 2),
            ];
        }

        return $curve;
    }

    public function getDailyPnl(?Collection $trades = null): array
    {
        $trades = $trades ?? $this->getTrades();
        $dateField = $this->journalType === 'manual' ? 'trade_date' : 'opened_at';

        return $trades->groupBy(fn ($t) => Carbon::parse($t->$dateField)->toDateString())
            ->map(fn ($group, $date) => [
                'date' => $date,
                'pnl' => round($group->sum('pnl'), 2),
                'trades' => $group->count(),
            ])
            ->values()
            ->toArray();
    }

    public function getWeeklyPnl(?Collection $trades = null): array
    {
        $trades = $trades ?? $this->getTrades();
        $dateField = $this->journalType === 'manual' ? 'trade_date' : 'opened_at';

        return $trades->groupBy(fn ($t) => Carbon::parse($t->$dateField)->startOfWeek()->toDateString())
            ->map(fn ($group, $week) => [
                'week' => $week,
                'pnl' => round($group->sum('pnl'), 2),
                'trades' => $group->count(),
                'win_rate' => $group->count() > 0
                    ? round($group->where('pnl', '>', 0)->count() / $group->count() * 100, 1)
                    : 0,
            ])
            ->values()
            ->toArray();
    }

    public function getPairDistribution(?Collection $trades = null): array
    {
        $trades = $trades ?? $this->getTrades();

        return $trades->groupBy(fn ($t) => $t->tradePair->symbol ?? 'N/A')
            ->map(fn ($group, $symbol) => [
                'symbol' => $symbol,
                'count' => $group->count(),
                'pnl' => round($group->sum('pnl'), 2),
                'win_rate' => $group->count() > 0
                    ? round($group->where('pnl', '>', 0)->count() / $group->count() * 100, 1)
                    : 0,
            ])
            ->sortByDesc('count')
            ->values()
            ->toArray();
    }

    public function getDirectionStats(?Collection $trades = null): array
    {
        $trades = $trades ?? $this->getTrades();
        $result = [];

        foreach (['long', 'short'] as $dir) {
            $dirTrades = $trades->where('direction', $dir);
            $count = $dirTrades->count();
            $result[$dir] = [
                'count' => $count,
                'pnl' => round($dirTrades->sum('pnl'), 2),
                'win_rate' => $count > 0
                    ? round($dirTrades->where('pnl', '>', 0)->count() / $count * 100, 1)
                    : 0,
            ];
        }

        return $result;
    }

    public function getSessionStats(?Collection $trades = null): array
    {
        if ($this->journalType !== 'manual') {
            return [];
        }

        $trades = $trades ?? $this->getTrades();
        $sessions = ['asian' => 'Asia', 'london' => 'Londres', 'new_york' => 'Nueva York', 'overlap' => 'Overlap'];
        $result = [];

        foreach ($sessions as $key => $label) {
            $sessionTrades = $trades->where('session', $key);
            $count = $sessionTrades->count();
            $result[] = [
                'session' => $label,
                'key' => $key,
                'count' => $count,
                'pnl' => round($sessionTrades->sum('pnl'), 2),
                'win_rate' => $count > 0
                    ? round($sessionTrades->where('pnl', '>', 0)->count() / $count * 100, 1)
                    : 0,
            ];
        }

        return $result;
    }

    public function getTimeframeStats(?Collection $trades = null): array
    {
        if ($this->journalType !== 'manual') {
            return [];
        }

        $trades = $trades ?? $this->getTrades();
        $timeframes = ManualTrade::timeframeOptions();
        $result = [];

        foreach ($timeframes as $key => $label) {
            $tfTrades = $trades->where('timeframe', $key);
            $count = $tfTrades->count();
            if ($count === 0) continue;

            $result[] = [
                'timeframe' => $label,
                'key' => $key,
                'count' => $count,
                'pnl' => round($tfTrades->sum('pnl'), 2),
                'win_rate' => round($tfTrades->where('pnl', '>', 0)->count() / $count * 100, 1),
            ];
        }

        return $result;
    }

    public function getMonthlyReturns(?Collection $trades = null): array
    {
        $trades = $trades ?? $this->getTrades();
        $dateField = $this->journalType === 'manual' ? 'trade_date' : 'opened_at';

        return $trades->groupBy(fn ($t) => Carbon::parse($t->$dateField)->format('Y-m'))
            ->map(fn ($group, $month) => [
                'month' => $month,
                'month_label' => Carbon::createFromFormat('Y-m', $month)->translatedFormat('M Y'),
                'trades' => $group->count(),
                'pnl' => round($group->sum('pnl'), 2),
                'win_rate' => $group->count() > 0
                    ? round($group->where('pnl', '>', 0)->count() / $group->count() * 100, 1)
                    : 0,
                'winners' => $group->where('pnl', '>', 0)->count(),
                'losers' => $group->where('pnl', '<', 0)->count(),
            ])
            ->sortKeys()
            ->values()
            ->toArray();
    }

    private function calculateStreaks(Collection $trades): array
    {
        $currentStreak = 0;
        $bestStreak = 0;
        $streak = 0;

        foreach ($trades as $trade) {
            if ($trade->pnl > 0) {
                $streak++;
                $bestStreak = max($bestStreak, $streak);
            } else {
                $streak = 0;
            }
        }

        return [
            'current' => $streak,
            'best' => $bestStreak,
        ];
    }

    private function emptyOverview(): array
    {
        return [
            'total_trades' => 0,
            'win_rate' => 0,
            'total_pnl' => 0,
            'profit_factor' => 0,
            'max_drawdown' => 0,
            'expectancy' => 0,
            'best_trade' => 0,
            'worst_trade' => 0,
            'avg_rr' => null,
            'profitable_days' => 0,
            'total_days' => 0,
            'current_streak' => 0,
            'best_streak' => 0,
            'avg_win' => 0,
            'avg_loss' => 0,
        ];
    }

    public function getStreaks(?Collection $trades = null): array
    {
        $trades = $trades ?? $this->getTrades();
        return $this->calculateStreaks($trades);
    }
}
