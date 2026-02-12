<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Services\PlatformStatsService;

class PageController extends Controller
{
    public function home(PlatformStatsService $statsService)
    {
        $stats = $statsService->getHomeStats();

        return view('pages.home', compact('stats'));
    }

    public function about()
    {
        $instructors = User::role('instructor')
            ->whereNotNull('bio')
            ->where('bio', '!=', '')
            ->get();

        return view('pages.about', compact('instructors'));
    }

    public function methodology()
    {
        return view('pages.methodology');
    }

    public function courses()
    {
        $courses = Course::published()
            ->withCount('modules', 'lessons')
            ->orderBy('sort_order')
            ->get();

        return view('pages.courses', compact('courses'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }
}
