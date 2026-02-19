<?php

namespace App\Filament\Resources\BrokerConnections\Schemas;

use App\Models\BrokerConnection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BrokerConnectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('status')
                ->label('Estado')
                ->options([
                    BrokerConnection::STATUS_ACTIVE => 'Activa',
                    BrokerConnection::STATUS_INACTIVE => 'Inactiva',
                    BrokerConnection::STATUS_ERROR => 'Error',
                ])
                ->required(),

            Toggle::make('sync_enabled')
                ->label('Sincronizacion activa'),

            Textarea::make('last_error')
                ->label('Ultimo error')
                ->disabled()
                ->columnSpanFull(),
        ]);
    }
}
