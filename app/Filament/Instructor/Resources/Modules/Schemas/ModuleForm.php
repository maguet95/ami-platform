<?php

namespace App\Filament\Instructor\Resources\Modules\Schemas;

use App\Models\Course;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Módulo')
                    ->schema([
                        Select::make('course_id')
                            ->label('Curso')
                            ->options(fn () => Course::where('instructor_id', auth()->id())->pluck('title', 'id'))
                            ->required()
                            ->searchable(),
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
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3),
                    ]),

                Section::make('Configuración')
                    ->columns(2)
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_published')
                            ->label('Publicado')
                            ->helperText('Los módulos no publicados son invisibles para los estudiantes.'),
                    ]),
            ]);
    }
}
