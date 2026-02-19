<?php

namespace App\Filament\Resources\BrokerConnections;

use App\Filament\Resources\BrokerConnections\Pages\EditBrokerConnection;
use App\Filament\Resources\BrokerConnections\Pages\ListBrokerConnections;
use App\Filament\Resources\BrokerConnections\Schemas\BrokerConnectionForm;
use App\Filament\Resources\BrokerConnections\Tables\BrokerConnectionsTable;
use App\Models\BrokerConnection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BrokerConnectionResource extends Resource
{
    protected static ?string $model = BrokerConnection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static ?string $navigationLabel = 'Conexiones';

    protected static ?string $modelLabel = 'Conexion';

    protected static ?string $pluralModelLabel = 'Conexiones';

    protected static UnitEnum|string|null $navigationGroup = 'Journal';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return BrokerConnectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrokerConnectionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBrokerConnections::route('/'),
            'edit' => EditBrokerConnection::route('/{record}/edit'),
        ];
    }
}
