<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $planName,
        public string $amount,
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
        return (new MailMessage)
            ->subject('Suscripcion activada — AMI Premium')
            ->markdown('mail.subscription-confirmed', [
                'user' => $notifiable,
                'planName' => $this->planName,
                'amount' => $this->amount,
            ]);
    }
}
