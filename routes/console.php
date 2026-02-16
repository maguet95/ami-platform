<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Email scheduled tasks
Schedule::command('email:subscription-expiring')->dailyAt('10:00');
Schedule::command('email:weekly-digest')->weeklyOn(1, '09:00'); // Mondays at 9:00

// Live class reminders (every 5 minutes)
Schedule::command('class:send-reminders')->everyFiveMinutes();
