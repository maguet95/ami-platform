<?php

namespace App\Filament\Resources\Achievements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AchievementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'learning' => 'Aprendizaje',
                        'engagement' => 'Compromiso',
                        'milestone' => 'Hito',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'learning' => 'info',
                        'engagement' => 'warning',
                        'milestone' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('tier')
                    ->label('Nivel')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'bronze' => 'Bronce',
                        'silver' => 'Plata',
                        'gold' => 'Oro',
                        'diamond' => 'Diamante',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'bronze' => 'warning',
                        'silver' => 'gray',
                        'gold' => 'warning',
                        'diamond' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('xp_reward')
                    ->label('XP')
                    ->sortable(),
                TextColumn::make('requirement_value')
                    ->label('Requisito')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
                TextColumn::make('users_count')
                    ->label('Ganados')
                    ->counts('users')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('category')
                    ->label('Categoría')
                    ->options([
                        'learning' => 'Aprendizaje',
                        'engagement' => 'Compromiso',
                        'milestone' => 'Hito',
                    ]),
                SelectFilter::make('tier')
                    ->label('Nivel')
                    ->options([
                        'bronze' => 'Bronce',
                        'silver' => 'Plata',
                        'gold' => 'Oro',
                        'diamond' => 'Diamante',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
