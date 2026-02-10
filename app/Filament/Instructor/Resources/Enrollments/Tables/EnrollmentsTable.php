<?php

namespace App\Filament\Instructor\Resources\Enrollments\Tables;

use App\Models\Course;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Estudiante')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('course.title')
                    ->label('Curso')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'active' => 'Activa',
                        'completed' => 'Completada',
                        'expired' => 'Expirada',
                        'cancelled' => 'Cancelada',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'active' => 'success',
                        'completed' => 'info',
                        'expired' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('progress_percent')
                    ->label('Progreso')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('enrolled_at')
                    ->label('Inscrito')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Completado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->defaultSort('enrolled_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'active' => 'Activa',
                        'completed' => 'Completada',
                        'expired' => 'Expirada',
                        'cancelled' => 'Cancelada',
                    ]),
                SelectFilter::make('course_id')
                    ->label('Curso')
                    ->options(fn () => Course::where('instructor_id', auth()->id())->pluck('title', 'id')),
            ]);
    }
}
