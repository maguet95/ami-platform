<?php

namespace App\Filament\Resources\LiveClasses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LiveClassesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                TextColumn::make('course.title')
                    ->label('Curso')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->placeholder('General'),
                TextColumn::make('instructor.name')
                    ->label('Instructor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('platform')
                    ->label('Plataforma')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'zoom' => 'Zoom',
                        'google_meet' => 'Google Meet',
                        'microsoft_teams' => 'MS Teams',
                        'discord' => 'Discord',
                        'other' => 'Otra',
                        default => $state,
                    })
                    ->color('info'),
                TextColumn::make('starts_at')
                    ->label('Fecha/Hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('duration_minutes')
                    ->label('Duración')
                    ->suffix(' min')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'scheduled' => 'Programada',
                        'in_progress' => 'En Progreso',
                        'completed' => 'Completada',
                        'cancelled' => 'Cancelada',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'scheduled' => 'info',
                        'in_progress' => 'success',
                        'completed' => 'gray',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('attendances_count')
                    ->label('Inscritos')
                    ->counts('attendances')
                    ->sortable(),
            ])
            ->defaultSort('starts_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'scheduled' => 'Programada',
                        'in_progress' => 'En Progreso',
                        'completed' => 'Completada',
                        'cancelled' => 'Cancelada',
                    ]),
                SelectFilter::make('platform')
                    ->label('Plataforma')
                    ->options([
                        'zoom' => 'Zoom',
                        'google_meet' => 'Google Meet',
                        'microsoft_teams' => 'Microsoft Teams',
                        'discord' => 'Discord',
                        'other' => 'Otra',
                    ]),
                SelectFilter::make('course_id')
                    ->label('Curso')
                    ->relationship('course', 'title'),
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
