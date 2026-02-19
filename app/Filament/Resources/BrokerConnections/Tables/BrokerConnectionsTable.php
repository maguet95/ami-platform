<?php

namespace App\Filament\Resources\BrokerConnections\Tables;

use App\Models\BrokerConnection;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BrokerConnectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => BrokerConnection::typeLabels()[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'binance' => 'warning',
                        default => 'info',
                    }),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'active' => 'success',
                        'error' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('sync_enabled')
                    ->label('Sync')
                    ->boolean(),
                TextColumn::make('last_synced_at')
                    ->label('Ultimo sync')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Nunca')
                    ->sortable(),
                TextColumn::make('last_error')
                    ->label('Error')
                    ->limit(50)
                    ->placeholder('—')
                    ->tooltip(fn ($record) => $record->last_error),
                TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(BrokerConnection::typeLabels()),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'active' => 'Activa',
                        'inactive' => 'Inactiva',
                        'error' => 'Error',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
