<?php

namespace App\Filament\Resources\AccessGrants;

use App\Filament\Resources\AccessGrants\Pages\CreateAccessGrant;
use App\Filament\Resources\AccessGrants\Pages\ListAccessGrants;
use App\Filament\Resources\AccessGrants\Schemas\AccessGrantForm;
use App\Filament\Resources\AccessGrants\Tables\AccessGrantsTable;
use App\Models\AccessGrant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AccessGrantResource extends Resource
{
    protected static ?string $model = AccessGrant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGiftTop;

    protected static ?string $navigationLabel = 'Accesos Especiales';

    protected static ?string $modelLabel = 'Acceso Especial';

    protected static ?string $pluralModelLabel = 'Accesos Especiales';

    protected static UnitEnum|string|null $navigationGroup = 'Usuarios';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return AccessGrantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AccessGrantsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAccessGrants::route('/'),
            'create' => CreateAccessGrant::route('/create'),
        ];
    }
}
