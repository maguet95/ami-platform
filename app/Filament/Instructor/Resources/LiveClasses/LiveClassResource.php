<?php

namespace App\Filament\Instructor\Resources\LiveClasses;

use App\Filament\Instructor\Resources\LiveClasses\Pages\CreateLiveClass;
use App\Filament\Instructor\Resources\LiveClasses\Pages\EditLiveClass;
use App\Filament\Instructor\Resources\LiveClasses\Pages\ListLiveClasses;
use App\Filament\Instructor\Resources\LiveClasses\Schemas\LiveClassForm;
use App\Filament\Instructor\Resources\LiveClasses\Tables\LiveClassesTable;
use App\Models\LiveClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class LiveClassResource extends Resource
{
    protected static ?string $model = LiveClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static ?string $navigationLabel = 'Mis Clases en Vivo';

    protected static ?string $modelLabel = 'Clase en Vivo';

    protected static ?string $pluralModelLabel = 'Mis Clases en Vivo';

    protected static UnitEnum|string|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 5;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('instructor_id', auth()->id());
    }

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
