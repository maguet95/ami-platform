<?php

namespace App\Filament\Resources\XpTransactions;

use App\Filament\Resources\XpTransactions\Pages\ListXpTransactions;
use App\Filament\Resources\XpTransactions\Tables\XpTransactionsTable;
use App\Models\XpTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class XpTransactionResource extends Resource
{
    protected static ?string $model = XpTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static ?string $navigationLabel = 'Transacciones XP';

    protected static ?string $modelLabel = 'Transacción XP';

    protected static ?string $pluralModelLabel = 'Transacciones XP';

    protected static UnitEnum|string|null $navigationGroup = 'Gamificación';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return XpTransactionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListXpTransactions::route('/'),
        ];
    }
}
