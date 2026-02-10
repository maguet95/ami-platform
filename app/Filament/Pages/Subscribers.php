<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use UnitEnum;

class Subscribers extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Suscriptores';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Suscriptores';

    protected static UnitEnum|string|null $navigationGroup = 'Pagos';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.subscribers';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->whereHas('subscriptions')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subscriptions.stripe_status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'active' => 'Activa',
                        'canceled' => 'Cancelada',
                        'past_due' => 'Vencida',
                        'incomplete' => 'Incompleta',
                        default => $state ?? 'N/A',
                    })
                    ->color(fn (?string $state) => match ($state) {
                        'active' => 'success',
                        'canceled' => 'danger',
                        'past_due' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('subscriptions.created_at')
                    ->label('Suscrito desde')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
