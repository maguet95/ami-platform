<?php

namespace App\Filament\Resources\JournalApiKeys\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;

class JournalApiKeyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(100)
                ->placeholder('ej: worker-trade-importer'),

            CheckboxList::make('permissions')
                ->label('Permisos')
                ->options([
                    'write:entries' => 'Escribir entradas de trading',
                    'write:summaries' => 'Escribir resumenes',
                ])
                ->helperText('Sin seleccion = todos los permisos'),

            TagsInput::make('allowed_ips')
                ->label('IPs permitidas')
                ->placeholder('Agregar IP...')
                ->helperText('Dejar vacio para permitir cualquier IP'),

            DateTimePicker::make('expires_at')
                ->label('Fecha de expiracion')
                ->nullable()
                ->helperText('Dejar vacio para que no expire'),

            Toggle::make('is_active')
                ->label('Activa')
                ->default(true),
        ]);
    }
}
