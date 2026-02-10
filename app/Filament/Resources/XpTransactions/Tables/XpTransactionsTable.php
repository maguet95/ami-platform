<?php

namespace App\Filament\Resources\XpTransactions\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class XpTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('XP')
                    ->sortable()
                    ->color('success')
                    ->prefix('+'),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'lesson' => 'Leccion',
                        'course' => 'Curso',
                        'login' => 'Login',
                        'streak' => 'Racha',
                        'achievement' => 'Logro',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'lesson' => 'info',
                        'course' => 'success',
                        'login' => 'gray',
                        'streak' => 'warning',
                        'achievement' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'lesson' => 'Leccion',
                        'course' => 'Curso',
                        'login' => 'Login',
                        'streak' => 'Racha',
                        'achievement' => 'Logro',
                    ]),
            ]);
    }
}
