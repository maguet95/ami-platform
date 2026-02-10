<?php

namespace App\Http\Controllers;

use App\Models\User;

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

        return view('profile.public-show', compact(
            'user', 'achievements', 'recentXp',
            'completedCourses', 'completedLessons', 'rank', 'isOwner'
        ));
    }
}
