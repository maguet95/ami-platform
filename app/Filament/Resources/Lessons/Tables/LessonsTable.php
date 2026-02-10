<?php

namespace App\Filament\Resources\Lessons\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LessonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('module.course.title')
                    ->label('Curso')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->toggleable(),
                TextColumn::make('module.title')
                    ->label('Módulo')
                    ->searchable()
                    ->sortable()
                    ->limit(25),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'video' => 'Video',
                        'text' => 'Texto',
                        'quiz' => 'Quiz',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'video' => 'info',
                        'text' => 'gray',
                        'quiz' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('duration_minutes')
                    ->label('Duración')
                    ->suffix(' min')
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Publicada')
                    ->boolean(),
                IconColumn::make('is_free_preview')
                    ->label('Preview')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                SelectFilter::make('module')
                    ->label('Módulo')
                    ->relationship('module', 'title'),
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'video' => 'Video',
                        'text' => 'Texto',
                        'quiz' => 'Quiz',
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
