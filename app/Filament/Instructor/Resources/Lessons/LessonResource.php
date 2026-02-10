<?php

namespace App\Filament\Instructor\Resources\Lessons;

use App\Filament\Instructor\Resources\Lessons\Pages\CreateLesson;
use App\Filament\Instructor\Resources\Lessons\Pages\EditLesson;
use App\Filament\Instructor\Resources\Lessons\Pages\ListLessons;
use App\Filament\Instructor\Resources\Lessons\Schemas\LessonForm;
use App\Filament\Instructor\Resources\Lessons\Tables\LessonsTable;
use App\Models\Lesson;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlayCircle;

    protected static ?string $navigationLabel = 'Lecciones';

    protected static ?string $modelLabel = 'Lección';

    protected static ?string $pluralModelLabel = 'Lecciones';

    protected static UnitEnum|string|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('module.course', fn (Builder $query) => $query->where('instructor_id', auth()->id()));
    }

    public static function form(Schema $schema): Schema
    {
        return LessonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LessonsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLessons::route('/'),
            'create' => CreateLesson::route('/create'),
            'edit' => EditLesson::route('/{record}/edit'),
        ];
    }
}
