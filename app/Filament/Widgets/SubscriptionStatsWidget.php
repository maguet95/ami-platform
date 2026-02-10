<?php

namespace App\Filament\Widgets;

use App\Models\Plan;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SubscriptionStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $activeSubscribers = User::whereHas('subscriptions', function ($query) {
            $query->where('stripe_status', 'active');
        })->count();

        $activePlans = Plan::active()->count();

        // Simple MRR calculation based on active subscriptions
        // In production, you'd query Stripe for accurate MRR
        $mrr = User::whereHas('subscriptions', function ($query) {
            $query->where('stripe_status', 'active');
        })->count() * 29.99; // Simplified estimate

        return [
            Stat::make('Suscriptores Activos', $activeSubscribers)
                ->icon('heroicon-o-users')
                ->color('success'),
            Stat::make('MRR Estimado', '$' . number_format($mrr, 2))
                ->icon('heroicon-o-currency-dollar')
                ->color('info'),
            Stat::make('Planes Activos', $activePlans)
                ->icon('heroicon-o-credit-card')
                ->color('warning'),
        ];
    }
}
