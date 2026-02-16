<?php

namespace App\Filament\Resources\LiveClasses;

use App\Filament\Resources\LiveClasses\Pages\CreateLiveClass;
use App\Filament\Resources\LiveClasses\Pages\EditLiveClass;
use App\Filament\Resources\LiveClasses\Pages\ListLiveClasses;
use App\Filament\Resources\LiveClasses\Schemas\LiveClassForm;
use App\Filament\Resources\LiveClasses\Tables\LiveClassesTable;
use App\Models\LiveClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LiveClassResource extends Resource
{
    protected static ?string $model = LiveClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static ?string $navigationLabel = 'Clases en Vivo';

    protected static ?string $modelLabel = 'Clase en Vivo';

    protected static ?string $pluralModelLabel = 'Clases en Vivo';

    protected static UnitEnum|string|null $navigationGroup = 'Educación';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return LiveClassForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LiveClassesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLiveClasses::route('/'),
            'create' => CreateLiveClass::route('/create'),
            'edit' => EditLiveClass::route('/{record}/edit'),
        ];
    }
}
