<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasContentOrganizerTree;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use UnitEnum;

class ContentOrganizer extends Page
{
    use HasContentOrganizerTree;

    protected static ?string $title = 'Organizador de Contenido';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?string $navigationLabel = 'Organizador';

    protected static UnitEnum|string|null $navigationGroup = 'Educacion';

    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.pages.content-organizer';

    /** @return array<int, array<string, mixed>> */
    public function getCourses(): array
    {
        return $this->loadTree();
    }

    protected function scopeQuery(Builder $query): Builder
    {
        return $query;
    }

    protected function getInstructorId(): ?int
    {
        return null;
    }

    /** @return array<int, Action> */
    protected function getHeaderActions(): array
    {
        return [
            $this->createCourseAction(),
        ];
    }

    protected function createCourseAction(): Action
    {
        return Action::make('createCourse')
            ->label('Crear Curso')
            ->icon(Heroicon::OutlinedPlus)
            ->form($this->getCourseFormFields())
            ->action(function (array $data) {
                $data['slug'] = Str::slug($data['title']);
                $data['sort_order'] = (Course::max('sort_order') ?? 0) + 1;

                if ($this->getInstructorId()) {
                    $data['instructor_id'] = $this->getInstructorId();
                }

                Course::create($data);
                Notification::make()->title('Curso creado')->success()->send();
                $this->dispatch('tree-updated', courses: $this->loadTree());
            });
    }

    public function editCourseAction(): Action
    {
        return Action::make('editCourse')
            ->form($this->getCourseFormFields())
            ->fillForm(function (array $arguments): array {
                $course = $this->validateCourseOwnership((int) $arguments['id']);

                return $course->toArray();
            })
            ->action(function (array $data, array $arguments) {
                $course = $this->validateCourseOwnership((int) $arguments['id']);
                $course->update($data);
                Notification::make()->title('Curso actualizado')->success()->send();
                $this->dispatch('tree-updated', courses: $this->loadTree());
            });
    }

    public function confirmDeleteCourseAction(): Action
    {
        return Action::make('confirmDeleteCourse')
            ->label('Eliminar')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Eliminar curso')
            ->modalDescription('Se eliminaran tambien todos los modulos y lecciones de este curso. Esta accion no se puede deshacer.')
            ->action(function (array $arguments) {
                $this->deleteCourseItem((int) $arguments['id']);
                Notification::make()->title('Curso eliminado')->success()->send();
            });
    }

    public function createModuleAction(): Action
    {
        return Action::make('createModule')
            ->label('Crear Modulo')
            ->form($this->getModuleFormFields())
            ->action(function (array $data) {
                $this->validateCourseOwnership((int) $data['course_id']);
                $data['slug'] = Str::slug($data['title']);
                $data['sort_order'] = (Module::where('course_id', $data['course_id'])->max('sort_order') ?? 0) + 1;
                Module::create($data);
                Notification::make()->title('Modulo creado')->success()->send();
                $this->dispatch('tree-updated', courses: $this->loadTree());
            });
    }

    public function editModuleAction(): Action
    {
        return Action::make('editModule')
            ->form($this->getModuleFormFields())
            ->fillForm(function (array $arguments): array {
                $module = $this->validateModuleOwnership((int) $arguments['id']);

                return $module->toArray();
            })
            ->action(function (array $data, array $arguments) {
                $module = $this->validateModuleOwnership((int) $arguments['id']);
                $module->update($data);
                Notification::make()->title('Modulo actualizado')->success()->send();
                $this->dispatch('tree-updated', courses: $this->loadTree());
            });
    }

    public function confirmDeleteModuleAction(): Action
    {
        return Action::make('confirmDeleteModule')
            ->label('Eliminar')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Eliminar modulo')
            ->modalDescription('Se eliminaran tambien todas las lecciones de este modulo.')
            ->action(function (array $arguments) {
                $this->deleteModuleItem((int) $arguments['id']);
                Notification::make()->title('Modulo eliminado')->success()->send();
            });
    }

    public function createLessonAction(): Action
    {
        return Action::make('createLesson')
            ->label('Crear Leccion')
            ->form($this->getLessonFormFields())
            ->action(function (array $data) {
                $this->validateModuleOwnership((int) $data['module_id']);
                $data['slug'] = Str::slug($data['title']);
                $data['sort_order'] = (Lesson::where('module_id', $data['module_id'])->max('sort_order') ?? 0) + 1;
                Lesson::create($data);
                Notification::make()->title('Leccion creada')->success()->send();
                $this->dispatch('tree-updated', courses: $this->loadTree());
            });
    }

    public function editLessonAction(): Action
    {
        return Action::make('editLesson')
            ->form($this->getLessonFormFields())
            ->fillForm(function (array $arguments): array {
                $lesson = $this->validateLessonOwnership((int) $arguments['id']);

                return $lesson->toArray();
            })
            ->action(function (array $data, array $arguments) {
                $lesson = $this->validateLessonOwnership((int) $arguments['id']);
                $lesson->update($data);
                Notification::make()->title('Leccion actualizada')->success()->send();
                $this->dispatch('tree-updated', courses: $this->loadTree());
            });
    }

    public function confirmDeleteLessonAction(): Action
    {
        return Action::make('confirmDeleteLesson')
            ->label('Eliminar')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Eliminar leccion')
            ->modalDescription('Esta accion no se puede deshacer.')
            ->action(function (array $arguments) {
                $this->deleteLessonItem((int) $arguments['id']);
                Notification::make()->title('Leccion eliminada')->success()->send();
            });
    }

    /** @return array<int, \Filament\Schemas\Components\Component> */
    protected function getCourseFormFields(): array
    {
        return [
            TextInput::make('title')
                ->label('Titulo')
                ->required()
                ->maxLength(255),
            Select::make('instructor_id')
                ->label('Instructor')
                ->relationship('instructor', 'name')
                ->searchable()
                ->preload()
                ->visible(fn () => $this->getInstructorId() === null),
            Select::make('status')
                ->label('Estado')
                ->options([
                    'draft' => 'Borrador',
                    'published' => 'Publicado',
                    'archived' => 'Archivado',
                ])
                ->default('draft')
                ->required(),
            Select::make('level')
                ->label('Nivel')
                ->options([
                    'beginner' => 'Principiante',
                    'intermediate' => 'Intermedio',
                    'advanced' => 'Avanzado',
                ])
                ->default('beginner')
                ->required(),
        ];
    }

    /** @return array<int, \Filament\Schemas\Components\Component> */
    protected function getModuleFormFields(): array
    {
        return [
            Select::make('course_id')
                ->label('Curso')
                ->options(fn () => $this->getCourseOptions())
                ->required()
                ->searchable(),
            TextInput::make('title')
                ->label('Titulo')
                ->required()
                ->maxLength(255),
            Toggle::make('is_published')
                ->label('Publicado')
                ->default(false),
        ];
    }

    /** @return array<int, \Filament\Schemas\Components\Component> */
    protected function getLessonFormFields(): array
    {
        return [
            Select::make('module_id')
                ->label('Modulo')
                ->options(fn () => $this->getModuleOptions())
                ->required()
                ->searchable(),
            TextInput::make('title')
                ->label('Titulo')
                ->required()
                ->maxLength(255),
            Select::make('type')
                ->label('Tipo')
                ->options([
                    'video' => 'Video',
                    'text' => 'Texto',
                    'quiz' => 'Quiz',
                ])
                ->default('video')
                ->required(),
            Toggle::make('is_published')
                ->label('Publicada')
                ->default(false),
            Toggle::make('is_free_preview')
                ->label('Vista previa gratuita')
                ->default(false),
        ];
    }

    /** @return array<int|string, string> */
    protected function getCourseOptions(): array
    {
        $query = Course::orderBy('title');
        /** @phpstan-ignore argument.type */
        $query = $this->scopeQuery($query);

        return $query->pluck('title', 'id')->toArray();
    }

    /** @return array<int|string, string> */
    protected function getModuleOptions(): array
    {
        $query = Module::whereHas('course', function (Builder $q) {
            $this->scopeQuery($q);
        })
            ->with('course')
            ->orderBy('course_id')
            ->orderBy('sort_order');

        return $query->get()
            ->mapWithKeys(fn (Module $m) => [$m->id => $m->course->title.' — '.$m->title])
            ->toArray();
    }
}
