<?php

namespace App\Console\Commands;

use App\Models\LiveClass;
use App\Notifications\LiveClassReminderNotification;
use Illuminate\Console\Command;

class SendLiveClassReminders extends Command
{
    protected $signature = 'class:send-reminders';

    protected $description = 'Send reminder notifications for live classes starting in the next 20 minutes';

    public function handle(): int
    {
        $classes = LiveClass::needsReminder()
            ->with(['attendances.user', 'instructor'])
            ->get();

        if ($classes->isEmpty()) {
            $this->info('No classes need reminders.');
            return self::SUCCESS;
        }

        $notified = 0;

        foreach ($classes as $class) {
            foreach ($class->attendances as $attendance) {
                if ($attendance->status === 'attended') {
                    continue;
                }

                $attendance->user->notify(
                    new LiveClassReminderNotification($class, $attendance->access_token)
                );

                $attendance->markNotified();
                $notified++;
            }

            $class->update(['notification_sent' => true]);
            $this->info("Sent reminders for: {$class->title} ({$class->attendances->count()} attendees)");
        }

        $this->info("Total notifications sent: {$notified}");

        return self::SUCCESS;
    }
}
