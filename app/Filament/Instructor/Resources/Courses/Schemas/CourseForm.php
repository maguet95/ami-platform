<?php

namespace App\Filament\Instructor\Resources\Courses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
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
                Hidden::make('instructor_id')
                    ->default(fn () => auth()->id()),

                Grid::make(3)->schema([
                    // Left Column (2/3)
                    Section::make('Informacion del Curso')
                        ->description('Los datos principales que veran tus estudiantes.')
                        ->columnSpan(2)
                        ->schema([
                            TextInput::make('title')
                                ->label('Titulo')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state ?? '')))
                                ->helperText('El titulo de tu curso tal como aparecera en el catalogo.'),
                            TextInput::make('slug')
                                ->label('URL amigable')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->helperText('Se genera automaticamente del titulo.'),
                            Textarea::make('short_description')
                                ->label('Descripcion corta')
                                ->rows(2)
                                ->maxLength(300)
                                ->helperText('Maximo 300 caracteres. Se muestra en las tarjetas del catalogo.'),
                            RichEditor::make('description')
                                ->label('Descripcion completa')
                                ->helperText('Contenido detallado del curso: temario, objetivos, requisitos previos.')
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
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->helperText('Solo los cursos publicados son visibles para los estudiantes.'),
                                DateTimePicker::make('published_at')
                                    ->label('Fecha de publicacion'),
                            ]),

                        Section::make('Detalles')
                            ->schema([
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
                                    ->label('Duracion (horas)')
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('horas')
                                    ->helperText('Duracion estimada total del curso.'),
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
