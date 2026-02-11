<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class PlatformStatsService
{
    public function getHomeStats(): array
    {
        return Cache::remember('platform_stats', 3600, function () {
            $students = User::count();
            $courses = Course::published()->count();
            $hours = (int) floor(Lesson::where('is_published', true)->sum('duration_minutes') / 60);

            return [
                'students' => $this->formatStat($students) . '+',
                'courses' => $this->formatStat($courses) . '+',
                'hours' => $this->formatStat($hours) . '+',
                'satisfaction' => '98%',
            ];
        });
    }

    private function formatStat(int $value): string
    {
        if ($value >= 100) {
            $rounded = (int) floor($value / 50) * 50;
        } elseif ($value >= 10) {
            $rounded = (int) floor($value / 10) * 10;
        } elseif ($value >= 5) {
            $rounded = (int) floor($value / 5) * 5;
        } else {
            $rounded = $value;
        }

        return (string) $rounded;
    }
}
