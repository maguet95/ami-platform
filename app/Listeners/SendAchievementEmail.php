<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Notifications\AchievementUnlockedNotification;

class SendAchievementEmail
{
    public function handle(AchievementUnlocked $event): void
    {
        $event->user->notify(new AchievementUnlockedNotification($event->achievement));
    }
}
