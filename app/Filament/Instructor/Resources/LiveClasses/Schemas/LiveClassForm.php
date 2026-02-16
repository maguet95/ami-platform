<?php

namespace App\Filament\Instructor\Resources\LiveClasses\Schemas;

use App\Models\Course;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LiveClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la Clase')
                    ->schema([
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->maxLength(1000),
                    ]),

                Grid::make(2)->schema([
                    Section::make('Programación')
                        ->schema([
                            Select::make('course_id')
                                ->label('Curso')
                                ->options(fn () => Course::where('instructor_id', auth()->id())->pluck('title', 'id'))
                                ->searchable()
                                ->helperText('Opcional. Solo tus cursos asignados.'),
                            DateTimePicker::make('starts_at')
                                ->label('Fecha y Hora de Inicio')
                                ->required()
                                ->native(false),
                            TextInput::make('duration_minutes')
                                ->label('Duración')
                                ->numeric()
                                ->default(60)
                                ->suffix('minutos')
                                ->required(),
                        ]),

                    Section::make('Plataforma')
                        ->schema([
                            Select::make('platform')
                                ->label('Plataforma')
                                ->options([
                                    'zoom' => 'Zoom',
                                    'google_meet' => 'Google Meet',
                                    'microsoft_teams' => 'Microsoft Teams',
                                    'discord' => 'Discord',
                                    'other' => 'Otra',
                                ])
                                ->required(),
                            TextInput::make('meeting_url')
                                ->label('URL de la Reunión')
                                ->url()
                                ->required()
                                ->maxLength(500),
                            TextInput::make('meeting_password')
                                ->label('Contraseña')
                                ->maxLength(100),
                            TextInput::make('max_attendees')
                                ->label('Máximo de Asistentes')
                                ->numeric()
                                ->helperText('Dejar vacío para sin límite.'),
                        ]),

                    Section::make('Notas')
                        ->schema([
                            Textarea::make('notes')
                                ->label('Notas')
                                ->rows(3),
                        ]),
                ]),
            ]);
    }
}
