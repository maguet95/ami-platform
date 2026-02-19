<?php

namespace App\Filament\Concerns;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasContentOrganizerTree
{
    /** @return array<int, array<string, mixed>> */
    public function loadTree(): array
    {
        $query = Course::with([
            'modules' => fn ($q) => $q->orderBy('sort_order')->with([
                'lessons' => fn ($q) => $q->orderBy('sort_order'),
            ]),
            'instructor',
        ])->orderBy('sort_order');

        /** @phpstan-ignore argument.type */
        $query = $this->scopeQuery($query);

        /** @var \Illuminate\Database\Eloquent\Collection<int, Course> $courses */
        $courses = $query->get();

        $result = [];
        foreach ($courses as $course) {
            /** @var User|null $instructor */
            $instructor = $course->instructor;

            $modulesData = [];
            foreach ($course->modules as $module) {
                $lessonsData = [];
                /** @var \Illuminate\Database\Eloquent\Collection<int, Lesson> $lessons */
                $lessons = $module->lessons;
                foreach ($lessons as $lesson) {
                    $lessonsData[] = [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'type' => $lesson->type,
                        'typeLabel' => $lesson->getTypeLabel(),
                        'is_published' => $lesson->is_published,
                        'is_free_preview' => $lesson->is_free_preview,
                        'duration_minutes' => $lesson->duration_minutes,
                        'module_id' => $lesson->module_id,
                    ];
                }

                $modulesData[] = [
                    'id' => $module->id,
                    'title' => $module->title,
                    'is_published' => $module->is_published,
                    'lessons_count' => count($lessonsData),
                    'course_id' => $module->course_id,
                    'lessons' => $lessonsData,
                ];
            }

            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'status' => $course->status,
                'statusLabel' => $course->getStatusLabel(),
                'level' => $course->level,
                'levelLabel' => $course->getLevelLabel(),
                'is_featured' => $course->is_featured,
                'instructor_name' => $instructor ? $instructor->name : null,
                'modules_count' => count($modulesData),
                'lessons_count' => array_sum(array_column($modulesData, 'lessons_count')),
                'modules' => $modulesData,
            ];
        }

        return $result;
    }

    /** @param array<int, int> $orderedIds */
    public function reorderCourses(array $orderedIds): void
    {
        DB::transaction(function () use ($orderedIds) {
            foreach ($orderedIds as $index => $id) {
                $query = Course::where('id', $id);
                /** @phpstan-ignore argument.type */
                $query = $this->scopeQuery($query);
                $query->update(['sort_order' => $index]);
            }
        });

        $this->dispatch('tree-updated', courses: $this->loadTree());
    }

    /** @param array<int, int> $orderedIds */
    public function reorderModules(int $courseId, array $orderedIds): void
    {
        $this->validateCourseOwnership($courseId);

        DB::transaction(function () use ($courseId, $orderedIds) {
            foreach ($orderedIds as $index => $id) {
                Module::where('id', $id)
                    ->where('course_id', $courseId)
                    ->update(['sort_order' => $index]);
            }
        });

        $this->dispatch('tree-updated', courses: $this->loadTree());
    }

    /** @param array<int, int> $orderedIds */
    public function reorderLessons(int $moduleId, array $orderedIds): void
    {
        $this->validateModuleOwnership($moduleId);

        DB::transaction(function () use ($moduleId, $orderedIds) {
            foreach ($orderedIds as $index => $id) {
                Lesson::where('id', $id)
                    ->where('module_id', $moduleId)
                    ->update(['sort_order' => $index]);
            }
        });

        $this->dispatch('tree-updated', courses: $this->loadTree());
    }

    public function moveLesson(int $lessonId, int $targetModuleId, int $position): void
    {
        $this->validateModuleOwnership($targetModuleId);

        /** @var Lesson $lesson */
        $lesson = Lesson::findOrFail($lessonId);
        $this->validateLessonOwnership($lesson->id);

        DB::transaction(function () use ($lesson, $targetModuleId, $position) {
            Lesson::where('module_id', $targetModuleId)
                ->where('sort_order', '>=', $position)
                ->increment('sort_order');

            $lesson->update([
                'module_id' => $targetModuleId,
                'sort_order' => $position,
            ]);
        });

        $this->dispatch('tree-updated', courses: $this->loadTree());
    }

    public function moveModule(int $moduleId, int $targetCourseId, int $position): void
    {
        $this->validateCourseOwnership($targetCourseId);

        /** @var Module $module */
        $module = Module::findOrFail($moduleId);
        $this->validateModuleOwnership($module->id);

        DB::transaction(function () use ($module, $targetCourseId, $position) {
            Module::where('course_id', $targetCourseId)
                ->where('sort_order', '>=', $position)
                ->increment('sort_order');

            $module->update([
                'course_id' => $targetCourseId,
                'sort_order' => $position,
            ]);
        });

        $this->dispatch('tree-updated', courses: $this->loadTree());
    }

    public function deleteCourseItem(int $courseId): void
    {
        $this->validateCourseOwnership($courseId);
        Course::findOrFail($courseId)->delete();
        $this->dispatch('tree-updated', courses: $this->loadTree());
    }

    public function deleteModuleItem(int $moduleId): void
    {
        $this->validateModuleOwnership($moduleId);
        Module::findOrFail($moduleId)->delete();
        $this->dispatch('tree-updated', courses: $this->loadTree());
    }

    public function deleteLessonItem(int $lessonId): void
    {
        $this->validateLessonOwnership($lessonId);
        Lesson::findOrFail($lessonId)->delete();
        $this->dispatch('tree-updated', courses: $this->loadTree());
    }

    protected function validateCourseOwnership(int $courseId): Course
    {
        $query = Course::where('id', $courseId);
        /** @phpstan-ignore argument.type */
        $query = $this->scopeQuery($query);

        /** @var Course */
        return $query->firstOrFail();
    }

    protected function validateModuleOwnership(int $moduleId): Module
    {
        /** @var Module $module */
        $module = Module::findOrFail($moduleId);
        $this->validateCourseOwnership($module->course_id);

        return $module;
    }

    protected function validateLessonOwnership(int $lessonId): Lesson
    {
        /** @var Lesson $lesson */
        $lesson = Lesson::findOrFail($lessonId);
        $this->validateModuleOwnership($lesson->module_id);

        return $lesson;
    }

    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return Builder<\Illuminate\Database\Eloquent\Model>
     */
    abstract protected function scopeQuery(Builder $query): Builder;
}
