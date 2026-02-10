<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentCourseController extends Controller
{
    public function index()
    {
        $enrollments = Auth::user()->enrollments()
            ->with('course')
            ->latest('enrolled_at')
            ->get();

        return view('student.my-courses', compact('enrollments'));
    }

    public function show(Course $course)
    {
        $user = Auth::user();

        // Check subscription for premium courses (progress is preserved)
        if (! $course->is_free && ! $user->hasActiveSubscription()) {
            return redirect()->route('pricing')
                ->with('error', 'Tu suscripción ha expirado. Renueva para seguir aprendiendo — tu progreso está guardado.');
        }

        $enrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->active()
            ->firstOrFail();

        $course->load(['modules.lessons' => function ($query) {
            $query->where('is_published', true)->orderBy('sort_order');
        }]);

        $completedLessonIds = $user->lessonProgress()
            ->where('is_completed', true)
            ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
            ->pluck('lesson_id')
            ->toArray();

        return view('student.course-show', compact('course', 'enrollment', 'completedLessonIds'));
    }

    public function lesson(Course $course, Lesson $lesson)
    {
        $user = Auth::user();

        // Check subscription for premium courses
        if (! $course->is_free && ! $user->hasActiveSubscription()) {
            return redirect()->route('pricing')
                ->with('error', 'Tu suscripción ha expirado. Renueva para seguir aprendiendo — tu progreso está guardado.');
        }

        $user->enrollments()
            ->where('course_id', $course->id)
            ->active()
            ->firstOrFail();

        $course->load(['modules.lessons' => function ($query) {
            $query->where('is_published', true)->orderBy('sort_order');
        }]);

        $completedLessonIds = $user->lessonProgress()
            ->where('is_completed', true)
            ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
            ->pluck('lesson_id')
            ->toArray();

        $progress = $user->lessonProgress()
            ->where('lesson_id', $lesson->id)
            ->first();

        return view('student.lesson-show', compact('course', 'lesson', 'completedLessonIds', 'progress'));
    }

    public function completeLesson(Request $request, Course $course, Lesson $lesson)
    {
        $user = Auth::user();

        $enrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->active()
            ->firstOrFail();

        $alreadyCompleted = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->where('is_completed', true)
            ->exists();

        LessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['is_completed' => true, 'completed_at' => now()]
        );

        $enrollment->recalculateProgress();

        // Gamification
        if (! $alreadyCompleted) {
            $gamification = app(GamificationService::class);
            $gamification->onLessonCompleted($user, $lesson);

            if ($enrollment->progress_percent >= 100) {
                $gamification->onCourseCompleted($user, $course);
            }
        }

        // Find next lesson
        $nextLesson = $this->getNextLesson($course, $lesson);

        if ($nextLesson) {
            return redirect()->route('student.lesson', [$course, $nextLesson]);
        }

        return redirect()->route('student.course', $course);
    }

    public function enroll(Course $course)
    {
        $user = Auth::user();

        if ($user->isEnrolledIn($course)) {
            return redirect()->route('student.course', $course);
        }

        if (! $course->isPublished()) {
            abort(404);
        }

        // Premium courses require an active subscription
        if (! $course->is_free && ! $user->hasActiveSubscription()) {
            return redirect()->route('pricing')
                ->with('error', 'Este curso requiere una suscripción activa.');
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        return redirect()->route('student.course', $course)
            ->with('success', '¡Te has inscrito exitosamente!');
    }

    private function getNextLesson(Course $course, Lesson $currentLesson): ?Lesson
    {
        $allLessons = $course->lessons()
            ->where('is_published', true)
            ->orderBy('modules.sort_order')
            ->orderBy('lessons.sort_order')
            ->get();

        $found = false;
        foreach ($allLessons as $lesson) {
            if ($found) {
                return $lesson;
            }
            if ($lesson->id === $currentLesson->id) {
                $found = true;
            }
        }

        return null;
    }
}
