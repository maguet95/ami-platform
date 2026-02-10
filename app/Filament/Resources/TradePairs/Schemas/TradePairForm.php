<?php

namespace App\Filament\Resources\TradePairs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TradePairForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('symbol')
                ->label('Simbolo')
                ->required()
                ->maxLength(20)
                ->placeholder('BTCUSDT'),

            Select::make('market')
                ->label('Mercado')
                ->required()
                ->options([
                    'crypto' => 'Crypto',
                    'forex' => 'Forex',
                    'stocks' => 'Acciones',
                ]),

            TextInput::make('display_name')
                ->label('Nombre visible')
                ->required()
                ->maxLength(50)
                ->placeholder('Bitcoin/USDT'),

            Toggle::make('is_active')
                ->label('Activo')
                ->default(true),
        ]);
    }
}
