<?php

namespace App\Http\Controllers;

use App\Models\LiveClass;
use App\Models\LiveClassAttendance;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrollments = $user->enrollments()->with('course')->active()->get();
        $enrolledCount = $enrollments->count();
        $completedLessons = $user->getCompletedLessonsCount();

        // Estimate study hours from completed lessons (avg ~15 min each)
        $studyHours = round($completedLessons * 0.25, 1);

        // Average progress across active enrollments
        $avgProgress = $enrolledCount > 0
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

        // Current enrollment to continue (from already loaded collection)
        $currentEnrollment = $enrollments
            ->where('progress_percent', '<', 100)
            ->sortByDesc('updated_at')
            ->first();

        // Upcoming live classes
        $upcomingClassIds = LiveClassAttendance::where('user_id', $user->id)
            ->pluck('live_class_id');

        $upcomingClasses = LiveClass::whereIn('id', $upcomingClassIds)
            ->where('status', 'scheduled')
            ->where('starts_at', '>=', now())
            ->with(['course', 'instructor'])
            ->orderBy('starts_at')
            ->limit(3)
            ->get();

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
            'upcomingClasses',
        ));
    }
}
