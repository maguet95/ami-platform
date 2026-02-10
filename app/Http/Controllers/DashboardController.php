<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrollments = $user->enrollments()->with('course')->active()->get();
        $enrolledCount = $user->enrollments()->count();
        $completedLessons = $user->getCompletedLessonsCount();

        // Estimate study hours from completed lessons (avg ~15 min each)
        $studyHours = round($completedLessons * 0.25, 1);

        // Average progress across active enrollments
        $avgProgress = $enrollments->count() > 0
            ? (int) round($enrollments->avg('progress_percent'))
            : 0;

        // Recent achievements
        $recentAchievements = $user->achievements()
            ->orderByPivot('earned_at', 'desc')
            ->limit(3)
            ->get();

        // Recent XP transactions
        $recentXp = $user->xpTransactions()
            ->latest('created_at')
            ->limit(5)
            ->get();

        // Current enrollment to continue
        $currentEnrollment = $user->enrollments()
            ->with('course')
            ->active()
            ->where('progress_percent', '<', 100)
            ->orderBy('updated_at', 'desc')
            ->first();

        return view('dashboard', compact(
            'user',
            'enrollments',
            'enrolledCount',
            'completedLessons',
            'studyHours',
            'avgProgress',
            'recentAchievements',
            'recentXp',
            'currentEnrollment',
        ));
    }
}
