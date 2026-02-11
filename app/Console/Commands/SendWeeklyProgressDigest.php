<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WeeklyProgressDigestNotification;
use Illuminate\Console\Command;

class SendWeeklyProgressDigest extends Command
{
    protected $signature = 'email:weekly-digest';

    protected $description = 'Send weekly progress digest to opted-in users';

    public function handle(): int
    {
        $weekAgo = now()->subWeek();

        $users = User::where('email_notifications', true)
            ->where('weekly_digest', true)
            ->whereNotNull('email_verified_at')
            ->get();

        $count = 0;

        foreach ($users as $user) {
            $lessonsCompleted = $user->lessonProgress()
                ->where('is_completed', true)
                ->where('completed_at', '>=', $weekAgo)
                ->count();

            $xpEarned = $user->xpTransactions()
                ->where('created_at', '>=', $weekAgo)
                ->sum('amount');

            $achievementsUnlocked = $user->achievements()
                ->wherePivot('earned_at', '>=', $weekAgo)
                ->count();

            $user->notify(new WeeklyProgressDigestNotification(
                $lessonsCompleted,
                (int) $xpEarned,
                $user->current_streak ?? 0,
                $achievementsUnlocked,
            ));

            $count++;
        }

        $this->info("Sent {$count} weekly digest emails.");

        return self::SUCCESS;
    }
}
