<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PlatformController extends Controller
{
    public function coursesCatalog()
    {
        $courses = Course::published()
            ->withCount('modules', 'lessons')
            ->orderBy('sort_order')
            ->get();

        return view('platform.courses-catalog', compact('courses'));
    }

    public function ranking()
    {
        $users = User::where('is_profile_public', true)
            ->where('total_xp', '>', 0)
            ->orderByDesc('total_xp')
            ->limit(100)
            ->get();

        $currentUser = Auth::user();

        return view('platform.ranking', compact('users', 'currentUser'));
    }
}
