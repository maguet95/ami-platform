<?php

namespace App\Filament\Instructor\Widgets;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InstructorStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $instructorId = auth()->id();

        $totalCourses = Course::where('instructor_id', $instructorId)->count();
        $publishedCourses = Course::where('instructor_id', $instructorId)->where('status', 'published')->count();
        $enrolledStudents = Enrollment::whereHas('course', fn ($q) => $q->where('instructor_id', $instructorId))
            ->where('status', 'active')
            ->count();
        $totalLessons = Lesson::whereHas('module.course', fn ($q) => $q->where('instructor_id', $instructorId))->count();

        // Completion rate
        $totalEnrollments = Enrollment::whereHas('course', fn ($q) => $q->where('instructor_id', $instructorId))->count();
        $completedEnrollments = Enrollment::whereHas('course', fn ($q) => $q->where('instructor_id', $instructorId))
            ->where('status', 'completed')
            ->count();
        $completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100) : 0;

        // Average progress
        $avgProgress = Enrollment::whereHas('course', fn ($q) => $q->where('instructor_id', $instructorId))
            ->where('status', 'active')
            ->avg('progress_percent') ?? 0;

        return [
            Stat::make('Mis Cursos', $totalCourses)
                ->icon('heroicon-o-academic-cap')
                ->color('primary'),
            Stat::make('Cursos Publicados', $publishedCourses)
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Estudiantes Inscritos', $enrolledStudents)
                ->icon('heroicon-o-users')
                ->color('info'),
            Stat::make('Lecciones Totales', $totalLessons)
                ->icon('heroicon-o-play-circle')
                ->color('warning'),
            Stat::make('Tasa de Completación', $completionRate . '%')
                ->icon('heroicon-o-chart-bar')
                ->color('success'),
            Stat::make('Progreso Promedio', round($avgProgress) . '%')
                ->icon('heroicon-o-arrow-trending-up')
                ->color('info'),
        ];
    }
}
