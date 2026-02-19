<?php

namespace App\Filament\Resources\TradePairs\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TradePairsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('symbol')
                    ->label('Simbolo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('market')
                    ->label('Mercado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'crypto' => 'Crypto',
                        'forex' => 'Forex',
                        'stocks' => 'Acciones',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'crypto' => 'warning',
                        'forex' => 'info',
                        'stocks' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('display_name')
                    ->label('Nombre')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
                TextColumn::make('trade_entries_count')
                    ->label('Trades')
                    ->counts('tradeEntries')
                    ->sortable(),
            ])
            ->defaultSort('symbol')
            ->filters([
                SelectFilter::make('market')
                    ->label('Mercado')
                    ->options([
                        'crypto' => 'Crypto',
                        'forex' => 'Forex',
                        'stocks' => 'Acciones',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
