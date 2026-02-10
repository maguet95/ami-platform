<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\XpTransaction;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GamificationStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $totalXpAwarded = XpTransaction::sum('amount');
        $activeStreaks = User::where('current_streak', '>=', 7)->count();
        $totalAchievementsEarned = DB::table('user_achievements')->count();
        $avgLevel = User::where('total_xp', '>', 0)->avg('total_xp');
        $avgLevel = $avgLevel ? max(1, (int) floor($avgLevel / 100) + 1) : 0;

        return [
            Stat::make('XP Total Otorgado', number_format($totalXpAwarded))
                ->icon('heroicon-o-bolt')
                ->color('warning'),
            Stat::make('Rachas Activas (7+)', $activeStreaks)
                ->icon('heroicon-o-fire')
                ->color('danger'),
            Stat::make('Logros Ganados', number_format($totalAchievementsEarned))
                ->icon('heroicon-o-trophy')
                ->color('success'),
            Stat::make('Nivel Promedio', $avgLevel)
                ->icon('heroicon-o-arrow-trending-up')
                ->color('info'),
        ];
    }
}
