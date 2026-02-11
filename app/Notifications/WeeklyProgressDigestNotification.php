<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyProgressDigestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $lessonsCompleted,
        public int $xpEarned,
        public int $currentStreak,
        public int $achievementsUnlocked,
    ) {}

    public function via(object $notifiable): array
    {
        if (! $notifiable->email_notifications || ! $notifiable->weekly_digest) {
            return [];
        }

        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu resumen semanal en AMI')
            ->markdown('mail.weekly-digest', [
                'user' => $notifiable,
                'lessonsCompleted' => $this->lessonsCompleted,
                'xpEarned' => $this->xpEarned,
                'currentStreak' => $this->currentStreak,
                'achievementsUnlocked' => $this->achievementsUnlocked,
            ]);
    }
}
