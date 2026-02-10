<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\User;

class GamificationService
{
    public function awardXp(User $user, int $amount, string $type, string $description, ?string $refType = null, ?int $refId = null): void
    {
        $user->addXp($amount, $type, $description, $refType, $refId);
        $this->checkAchievements($user);
    }

    public function checkAchievements(User $user): void
    {
        $user->refresh();

        $earnedIds = $user->achievements()->pluck('achievements.id')->toArray();

        $eligible = Achievement::active()
            ->whereNotIn('id', $earnedIds)
            ->get();

        foreach ($eligible as $achievement) {
            if ($this->meetsRequirement($user, $achievement)) {
                $user->achievements()->attach($achievement->id, [
                    'earned_at' => now(),
                ]);

                if ($achievement->xp_reward > 0) {
                    $user->addXp(
                        $achievement->xp_reward,
                        'achievement',
                        "Logro: {$achievement->name}",
                        Achievement::class,
                        $achievement->id
                    );
                }
            }
        }
    }

    public function onLessonCompleted(User $user, Lesson $lesson): void
    {
        $xp = config('gamification.xp.lesson_completed', 10);
        $this->awardXp($user, $xp, 'lesson', "Leccion completada: {$lesson->title}", Lesson::class, $lesson->id);
    }

    public function onCourseCompleted(User $user, Course $course): void
    {
        $xp = config('gamification.xp.course_completed', 100);
        $this->awardXp($user, $xp, 'course', "Curso completado: {$course->title}", Course::class, $course->id);
    }

    public function onDailyLogin(User $user): void
    {
        $today = now()->toDateString();

        if ($user->last_active_date && $user->last_active_date->toDateString() === $today) {
            return;
        }

        $user->recordLogin();

        // Calculate streak
        $yesterday = now()->subDay()->toDateString();

        if ($user->last_active_date && $user->last_active_date->toDateString() === $yesterday) {
            $user->current_streak++;
        } else {
            $user->current_streak = 1;
        }

        if ($user->current_streak > $user->longest_streak) {
            $user->longest_streak = $user->current_streak;
        }

        $user->last_active_date = $today;
        $user->save();

        // Award daily login XP
        $xp = config('gamification.xp.daily_login', 5);
        $this->awardXp($user, $xp, 'login', 'Login diario');

        // Award streak bonuses
        $streak = $user->current_streak;
        if ($streak === 7) {
            $this->awardXp($user, config('gamification.xp.streak_7', 50), 'streak', 'Racha de 7 dias');
        } elseif ($streak === 30) {
            $this->awardXp($user, config('gamification.xp.streak_30', 200), 'streak', 'Racha de 30 dias');
        }
    }

    public function recalculateXp(User $user): void
    {
        $total = $user->xpTransactions()->sum('amount');
        $user->update(['total_xp' => $total]);
    }

    private function meetsRequirement(User $user, Achievement $achievement): bool
    {
        return match ($achievement->requirement_type) {
            'lessons_completed' => $user->getCompletedLessonsCount() >= $achievement->requirement_value,
            'courses_completed' => $user->getCompletedCoursesCount() >= $achievement->requirement_value,
            'login_streak' => $user->longest_streak >= $achievement->requirement_value,
            'total_xp' => $user->total_xp >= $achievement->requirement_value,
            default => false,
        };
    }
}
