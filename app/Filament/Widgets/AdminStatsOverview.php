<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Usuarios', User::count())
                ->icon('heroicon-o-user-group')
                ->color('primary'),
            Stat::make('Cursos Publicados', Course::where('status', 'published')->count())
                ->icon('heroicon-o-academic-cap')
                ->color('success'),
            Stat::make('Inscripciones Activas', Enrollment::where('status', 'active')->count())
                ->icon('heroicon-o-clipboard-document-list')
                ->color('info'),
            Stat::make('Suscriptores Activos', User::whereHas('subscriptions', function ($query) {
                $query->where('stripe_status', 'active');
            })->count())
                ->icon('heroicon-o-credit-card')
                ->color('warning'),
        ];
    }
}
