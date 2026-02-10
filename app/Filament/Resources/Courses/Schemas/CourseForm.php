<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    // Left Column (2/3)
                    Section::make('Información del Curso')
                        ->columnSpan(2)
                        ->schema([
                            TextInput::make('title')
                                ->label('Título')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                            TextInput::make('slug')
                                ->label('URL amigable')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                            Textarea::make('short_description')
                                ->label('Descripción corta')
                                ->rows(2)
                                ->maxLength(300)
                                ->helperText('Máximo 300 caracteres. Se muestra en las tarjetas del catálogo.'),
                            RichEditor::make('description')
                                ->label('Descripción completa')
                                ->columnSpanFull(),
                        ]),

                    // Right Column (1/3)
                    Grid::make(1)->columnSpan(1)->schema([
                        Section::make('Estado')
                            ->schema([
                                Select::make('status')
                                    ->label('Estado')
                                    ->options([
                                        'draft' => 'Borrador',
                                        'published' => 'Publicado',
                                        'archived' => 'Archivado',
                                    ])
                                    ->default('draft')
                                    ->required(),
                                DateTimePicker::make('published_at')
                                    ->label('Fecha de publicación'),
                                Toggle::make('is_featured')
                                    ->label('Destacado')
                                    ->helperText('Aparece en la sección destacada del home.'),
                            ]),

                        Section::make('Detalles')
                            ->schema([
                                Select::make('instructor_id')
                                    ->label('Instructor')
                                    ->relationship('instructor', 'name')
                                    ->searchable()
                                    ->preload(),
                                Select::make('level')
                                    ->label('Nivel')
                                    ->options([
                                        'beginner' => 'Principiante',
                                        'intermediate' => 'Intermedio',
                                        'advanced' => 'Avanzado',
                                    ])
                                    ->default('beginner')
                                    ->required(),
                                TextInput::make('duration_hours')
                                    ->label('Duración (horas)')
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('horas'),
                                TextInput::make('sort_order')
                                    ->label('Orden')
                                    ->numeric()
                                    ->default(0),
                            ]),

                        Section::make('Precio')
                            ->schema([
                                Toggle::make('is_free')
                                    ->label('Curso gratuito')
                                    ->live(),
                                TextInput::make('price')
                                    ->label('Precio')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('$')
                                    ->hidden(fn ($get) => $get('is_free')),
                                Select::make('currency')
                                    ->label('Moneda')
                                    ->options([
                                        'USD' => 'USD',
                                        'COP' => 'COP',
                                        'EUR' => 'EUR',
                                    ])
                                    ->default('USD')
                                    ->hidden(fn ($get) => $get('is_free')),
                            ]),

                        Section::make('Imagen')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Imagen de portada')
                                    ->image()
                                    ->directory('courses')
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('16:9'),
                            ]),
                    ]),
                ]),
            ]);
    }
}
