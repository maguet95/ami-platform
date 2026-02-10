<?php

namespace App\Filament\Resources\Lessons\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la Lección')
                    ->schema([
                        Select::make('module_id')
                            ->label('Módulo')
                            ->relationship('module', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->label('URL amigable')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->label('Tipo de contenido')
                            ->options([
                                'video' => 'Video',
                                'text' => 'Texto',
                                'quiz' => 'Quiz',
                            ])
                            ->default('video')
                            ->required()
                            ->live(),
                    ]),

                Section::make('Contenido')
                    ->schema([
                        TextInput::make('video_url')
                            ->label('URL del video')
                            ->url()
                            ->helperText('URL de Bunny.net Stream, YouTube o Vimeo.')
                            ->hidden(fn ($get) => $get('type') !== 'video'),
                        Select::make('video_provider')
                            ->label('Proveedor de video')
                            ->options([
                                'bunny' => 'Bunny.net Stream',
                                'youtube' => 'YouTube',
                                'vimeo' => 'Vimeo',
                            ])
                            ->hidden(fn ($get) => $get('type') !== 'video'),
                        RichEditor::make('content')
                            ->label('Contenido')
                            ->columnSpanFull(),
                    ]),

                Section::make('Configuración')
                    ->columns(2)
                    ->schema([
                        TextInput::make('duration_minutes')
                            ->label('Duración (minutos)')
                            ->numeric()
                            ->default(0)
                            ->suffix('min'),
                        TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_published')
                            ->label('Publicada'),
                        Toggle::make('is_free_preview')
                            ->label('Vista previa gratuita')
                            ->helperText('Permite que usuarios no inscritos vean esta lección.'),
                    ]),
            ]);
    }
}
