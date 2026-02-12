<?php

namespace App\Filament\Resources\AccessGrants\Schemas;

use App\Models\AccessGrant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AccessGrantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('email')
                ->label('Correo electrónico')
                ->email()
                ->required()
                ->maxLength(255)
                ->placeholder('usuario@ejemplo.com'),

            Select::make('duration_type')
                ->label('Duración')
                ->options(AccessGrant::durationOptions())
                ->default(AccessGrant::DURATION_1_MONTH)
                ->required(),

            Textarea::make('notes')
                ->label('Notas')
                ->rows(3)
                ->maxLength(500)
                ->placeholder('Razón del acceso, referencia, etc.'),
        ]);
    }
}
