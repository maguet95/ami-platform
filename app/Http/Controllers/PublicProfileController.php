<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Services\TradingStatsService;

class PublicProfileController extends Controller
{
    public function show(User $user)
    {
        $isOwner = auth()->check() && auth()->id() === $user->id;

        if (! $user->is_profile_public && ! $isOwner) {
            abort(404);
        }

        $achievements = $user->achievements()
            ->orderByPivot('earned_at', 'desc')
            ->get();

        $recentXp = $user->xpTransactions()
            ->latest('created_at')
            ->limit(10)
            ->get();

        $completedCourses = $user->getCompletedCoursesCount();
        $completedLessons = $user->getCompletedLessonsCount();
        $rank = $user->getRank();

        // Journal stats for public profile
        $manualJournalStats = null;
        $manualEquityCurve = null;
        $manualPairDistribution = null;
        $automaticJournalStats = null;
        $automaticEquityCurve = null;
        $automaticPairDistribution = null;

        if ($user->share_manual_journal) {
            $manualService = TradingStatsService::manual($user->id);
            $manualJournalStats = $manualService->getOverviewMetrics();
            if ($manualJournalStats['total_trades'] > 0) {
                $manualEquityCurve = $manualService->getEquityCurve();
                $manualPairDistribution = $manualService->getPairDistribution();
            }
        }

        if ($user->share_automatic_journal) {
            $autoService = TradingStatsService::automatic($user->id);
            $automaticJournalStats = $autoService->getOverviewMetrics();
            if ($automaticJournalStats['total_trades'] > 0) {
                $automaticEquityCurve = $autoService->getEquityCurve();
                $automaticPairDistribution = $autoService->getPairDistribution();
            }
        }

        $instructorCourses = collect();
        if ($user->hasRole('instructor')) {
            $instructorCourses = Course::where('instructor_id', $user->id)
                ->published()
                ->withCount('lessons')
                ->orderBy('sort_order')
                ->get();
        }

        return view('profile.public-show', compact(
            'user', 'achievements', 'recentXp',
            'completedCourses', 'completedLessons', 'rank', 'isOwner',
            'manualJournalStats', 'manualEquityCurve', 'manualPairDistribution',
            'automaticJournalStats', 'automaticEquityCurve', 'automaticPairDistribution',
            'instructorCourses',
        ));
    }
}
