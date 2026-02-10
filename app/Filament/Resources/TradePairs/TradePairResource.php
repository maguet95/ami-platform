<?php

namespace App\Filament\Resources\TradePairs;

use App\Filament\Resources\TradePairs\Pages\CreateTradePair;
use App\Filament\Resources\TradePairs\Pages\EditTradePair;
use App\Filament\Resources\TradePairs\Pages\ListTradePairs;
use App\Filament\Resources\TradePairs\Schemas\TradePairForm;
use App\Filament\Resources\TradePairs\Tables\TradePairsTable;
use App\Models\TradePair;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TradePairResource extends Resource
{
    protected static ?string $model = TradePair::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $navigationLabel = 'Pares de Trading';

    protected static ?string $modelLabel = 'Par';

    protected static ?string $pluralModelLabel = 'Pares de Trading';

    protected static UnitEnum|string|null $navigationGroup = 'Journal';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return TradePairForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TradePairsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTradePairs::route('/'),
            'create' => CreateTradePair::route('/create'),
            'edit' => EditTradePair::route('/{record}/edit'),
        ];
    }
}
