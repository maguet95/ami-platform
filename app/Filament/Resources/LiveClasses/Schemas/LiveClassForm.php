<?php

namespace App\Filament\Resources\LiveClasses\Schemas;

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
                                ->relationship('course', 'title')
                                ->searchable()
                                ->preload()
                                ->helperText('Opcional. Si se asocia, se registran automáticamente los estudiantes inscritos.'),
                            Select::make('instructor_id')
                                ->label('Instructor')
                                ->relationship('instructor', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
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
                                ->maxLength(100)
                                ->helperText('Opcional. Se incluirá en la notificación.'),
                            TextInput::make('max_attendees')
                                ->label('Máximo de Asistentes')
                                ->numeric()
                                ->helperText('Dejar vacío para sin límite.'),
                            Select::make('status')
                                ->label('Estado')
                                ->options([
                                    'scheduled' => 'Programada',
                                    'in_progress' => 'En Progreso',
                                    'completed' => 'Completada',
                                    'cancelled' => 'Cancelada',
                                ])
                                ->default('scheduled')
                                ->required(),
                        ]),

                    Section::make('Notas')
                        ->schema([
                            Textarea::make('notes')
                                ->label('Notas internas')
                                ->rows(3)
                                ->helperText('Solo visible para administradores e instructores.'),
                        ]),
                ]),
            ]);
    }
}
