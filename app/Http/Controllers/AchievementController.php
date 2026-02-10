<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $earnedIds = $user->achievements()->pluck('achievements.id')->toArray();

        $achievements = Achievement::active()->orderBy('sort_order')->get();

        // Group by category
        $categories = $achievements->groupBy('category');

        // Calculate progress for each achievement
        $achievements->each(function ($achievement) use ($user, $earnedIds) {
            $achievement->is_earned = in_array($achievement->id, $earnedIds);
            $achievement->earned_at = $achievement->is_earned
                ? $user->achievements()->where('achievements.id', $achievement->id)->first()?->pivot->earned_at
                : null;
            $achievement->progress = $this->calculateProgress($user, $achievement);
        });

        $totalCount = $achievements->count();
        $earnedCount = $achievements->where('is_earned', true)->count();
        $totalXpFromAchievements = $achievements->where('is_earned', true)->sum('xp_reward');

        return view('pages.achievements', compact(
            'categories',
            'totalCount',
            'earnedCount',
            'totalXpFromAchievements',
        ));
    }

    private function calculateProgress($user, Achievement $achievement): array
    {
        $current = match ($achievement->requirement_type) {
            'lessons_completed' => $user->getCompletedLessonsCount(),
            'courses_completed' => $user->getCompletedCoursesCount(),
            'login_streak' => $user->longest_streak ?? 0,
            'total_xp' => $user->total_xp ?? 0,
            default => 0,
        };

        $target = $achievement->requirement_value;
        $percent = $target > 0 ? min(100, round(($current / $target) * 100)) : 0;

        return [
            'current' => $current,
            'target' => $target,
            'percent' => $percent,
        ];
    }
}
