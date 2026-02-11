<?php

namespace App\Notifications;

use App\Models\Achievement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AchievementUnlockedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Achievement $achievement,
    ) {}

    public function via(object $notifiable): array
    {
        if (! $notifiable->email_notifications) {
            return [];
        }

        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $tierLabel = $this->achievement->getTierLabel();
        $xp = $this->achievement->xp_reward;

        return (new MailMessage)
            ->subject("Nuevo logro: {$this->achievement->name}")
            ->markdown('mail.achievement-unlocked', [
                'user' => $notifiable,
                'achievement' => $this->achievement,
                'tierLabel' => $tierLabel,
                'xp' => $xp,
            ]);
    }
}
