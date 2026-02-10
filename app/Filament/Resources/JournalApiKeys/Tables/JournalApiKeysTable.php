<?php

namespace App\Filament\Resources\JournalApiKeys\Tables;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JournalApiKeysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('key_prefix')
                    ->label('Prefijo')
                    ->formatStateUsing(fn (string $state) => $state . '...')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('permissions')
                    ->label('Permisos')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) {
                            return 'Todos';
                        }
                        return implode(', ', $state);
                    }),
                IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),
                TextColumn::make('last_used_at')
                    ->label('Ultimo uso')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Nunca')
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label('Expira')
                    ->dateTime('d/m/Y')
                    ->placeholder('Sin expiracion')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
