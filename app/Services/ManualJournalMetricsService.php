<?php

namespace App\Services;

use App\Models\ManualTrade;
use Illuminate\Support\Facades\Cache;

class ManualJournalMetricsService
{
    public function getMetrics(int $userId): array
    {
        $cacheKey = "manual_journal_metrics:{$userId}";

        return Cache::remember($cacheKey, 300, function () use ($userId) {
            return $this->calculateMetrics($userId);
        });
    }

    public function invalidateCache(int $userId): void
    {
        Cache::forget("manual_journal_metrics:{$userId}");
    }

    private function calculateMetrics(int $userId): array
    {
        $trades = ManualTrade::forUser($userId)->closed()->get();

        if ($trades->isEmpty()) {
            return $this->emptyMetrics();
        }

        $totalTrades = $trades->count();
        $winners = $trades->where('pnl', '>', 0);
        $losers = $trades->where('pnl', '<', 0);
        $winCount = $winners->count();
        $loseCount = $losers->count();

        $totalPnl = $trades->sum('pnl');
        $avgPnl = $trades->avg('pnl');
        $maxWin = $trades->max('pnl') ?? 0;
        $maxLoss = $trades->min('pnl') ?? 0;

        $avgRrPlanned = $trades->whereNotNull('risk_reward_planned')->avg('risk_reward_planned');
        $avgRrActual = $trades->whereNotNull('risk_reward_actual')->avg('risk_reward_actual');

        $avgRating = $trades->whereNotNull('overall_rating')->avg('overall_rating');
        $avgConfidence = $trades->whereNotNull('confidence_level')->avg('confidence_level');

        // Streaks — ordered by date
        $ordered = ManualTrade::forUser($userId)
            ->closed()
            ->whereNotNull('pnl')
            ->orderBy('trade_date')
            ->pluck('pnl');

        $currentStreak = 0;
        $bestStreak = 0;
        $streak = 0;

        foreach ($ordered as $pnl) {
            if ($pnl > 0) {
                $streak++;
                $bestStreak = max($bestStreak, $streak);
            } else {
                $streak = 0;
            }
        }
        $currentStreak = $streak;

        // Open trades count
        $openTrades = ManualTrade::forUser($userId)->open()->count();

        return [
            'total_trades' => $totalTrades,
            'open_trades' => $openTrades,
            'win_rate' => $totalTrades > 0 ? round(($winCount / $totalTrades) * 100, 1) : 0,
            'win_count' => $winCount,
            'lose_count' => $loseCount,
            'total_pnl' => round($totalPnl, 2),
            'avg_pnl' => round($avgPnl, 2),
            'max_win' => round($maxWin, 2),
            'max_loss' => round($maxLoss, 2),
            'avg_rr_planned' => $avgRrPlanned ? round($avgRrPlanned, 2) : null,
            'avg_rr_actual' => $avgRrActual ? round($avgRrActual, 2) : null,
            'avg_rating' => $avgRating ? round($avgRating, 1) : null,
            'avg_confidence' => $avgConfidence ? round($avgConfidence, 1) : null,
            'current_streak' => $currentStreak,
            'best_streak' => $bestStreak,
        ];
    }

    private function emptyMetrics(): array
    {
        return [
            'total_trades' => 0,
            'open_trades' => 0,
            'win_rate' => 0,
            'win_count' => 0,
            'lose_count' => 0,
            'total_pnl' => 0,
            'avg_pnl' => 0,
            'max_win' => 0,
            'max_loss' => 0,
            'avg_rr_planned' => null,
            'avg_rr_actual' => null,
            'avg_rating' => null,
            'avg_confidence' => null,
            'current_streak' => 0,
            'best_streak' => 0,
        ];
    }
}
