<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Usuario')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(255),
                        Select::make('roles')
                            ->label('Roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload(),
                    ]),
                Section::make('Perfil Público')
                    ->schema([
                        TextInput::make('username')
                            ->label('Username')
                            ->unique(ignoreRecord: true)
                            ->maxLength(30),
                        TextInput::make('bio')
                            ->label('Bio')
                            ->maxLength(500),
                        TextInput::make('location')
                            ->label('Ubicación')
                            ->maxLength(100),
                        TextInput::make('twitter_handle')
                            ->label('Twitter')
                            ->maxLength(50),
                        DatePicker::make('trading_since')
                            ->label('Trading desde'),
                        Toggle::make('is_profile_public')
                            ->label('Perfil público'),
                    ]),
                Section::make('Gamificación')
                    ->schema([
                        TextInput::make('total_xp')
                            ->label('XP Total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('current_streak')
                            ->label('Racha actual')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('longest_streak')
                            ->label('Racha más larga')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->hiddenOn('create'),
            ]);
    }
}
