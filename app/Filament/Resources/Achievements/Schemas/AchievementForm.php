<?php

namespace App\Filament\Resources\Achievements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AchievementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Logro')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->required()
                            ->rows(2),
                        TextInput::make('icon')
                            ->label('Ícono')
                            ->default('heroicon-o-trophy')
                            ->helperText('Nombre del ícono Heroicon (ej: heroicon-o-trophy).'),
                    ]),
                Section::make('Requisitos')
                    ->schema([
                        Select::make('category')
                            ->label('Categoría')
                            ->options([
                                'learning' => 'Aprendizaje',
                                'engagement' => 'Compromiso',
                                'milestone' => 'Hito',
                            ])
                            ->required(),
                        Select::make('requirement_type')
                            ->label('Tipo de Requisito')
                            ->options([
                                'lessons_completed' => 'Lecciones completadas',
                                'courses_completed' => 'Cursos completados',
                                'login_streak' => 'Racha de login',
                                'total_xp' => 'XP total',
                            ])
                            ->required(),
                        TextInput::make('requirement_value')
                            ->label('Valor del Requisito')
                            ->numeric()
                            ->required()
                            ->default(1),
                        TextInput::make('xp_reward')
                            ->label('Recompensa XP')
                            ->numeric()
                            ->required()
                            ->default(0),
                        Select::make('tier')
                            ->label('Nivel')
                            ->options([
                                'bronze' => 'Bronce',
                                'silver' => 'Plata',
                                'gold' => 'Oro',
                                'diamond' => 'Diamante',
                            ])
                            ->default('bronze')
                            ->required(),
                    ]),
                Section::make('Configuración')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ]),
            ]);
    }
}
