<?php

namespace App\Filament\Resources\JournalApiKeys;

use App\Filament\Resources\JournalApiKeys\Pages\CreateJournalApiKey;
use App\Filament\Resources\JournalApiKeys\Pages\EditJournalApiKey;
use App\Filament\Resources\JournalApiKeys\Pages\ListJournalApiKeys;
use App\Filament\Resources\JournalApiKeys\Schemas\JournalApiKeyForm;
use App\Filament\Resources\JournalApiKeys\Tables\JournalApiKeysTable;
use App\Models\JournalApiKey;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class JournalApiKeyResource extends Resource
{
    protected static ?string $model = JournalApiKey::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $navigationLabel = 'API Keys';

    protected static ?string $modelLabel = 'API Key';

    protected static ?string $pluralModelLabel = 'API Keys';

    protected static UnitEnum|string|null $navigationGroup = 'Journal';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return JournalApiKeyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JournalApiKeysTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJournalApiKeys::route('/'),
            'create' => CreateJournalApiKey::route('/create'),
            'edit' => EditJournalApiKey::route('/{record}/edit'),
        ];
    }
}
