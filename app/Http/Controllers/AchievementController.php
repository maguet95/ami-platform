<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Load all earned achievements with pivot data in a single query
        $earnedMap = $user->achievements()->get()->keyBy('id');

        $achievements = Achievement::active()->orderBy('sort_order')->get();

        // Pre-compute stats once (avoids repeated queries per achievement)
        $stats = [
            'lessons_completed' => $user->getCompletedLessonsCount(),
            'courses_completed' => $user->getCompletedCoursesCount(),
            'login_streak' => $user->longest_streak ?? 0,
            'total_xp' => $user->total_xp ?? 0,
        ];

        $achievements->each(function ($achievement) use ($earnedMap, $stats) {
            $earned = $earnedMap->get($achievement->id);
            $achievement->is_earned = $earned !== null;
            $achievement->earned_at = $earned?->pivot->earned_at;
            $achievement->progress = $this->calculateProgress($stats, $achievement);
        });

        $categories = $achievements->groupBy('category');
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

    private function calculateProgress(array $stats, Achievement $achievement): array
    {
        $current = $stats[$achievement->requirement_type] ?? 0;
        $target = $achievement->requirement_value;
        $percent = $target > 0 ? min(100, round(($current / $target) * 100)) : 0;

        return [
            'current' => $current,
            'target' => $target,
            'percent' => $percent,
        ];
    }
}
