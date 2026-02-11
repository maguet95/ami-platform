<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringSoonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $daysLeft,
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
        $dayWord = $this->daysLeft === 1 ? 'dia' : 'dias';

        return (new MailMessage)
            ->subject('Tu suscripcion vence pronto')
            ->greeting("Hola {$notifiable->name},")
            ->line("Tu suscripcion en AMI vence en **{$this->daysLeft} {$dayWord}**.")
            ->line('Si no la renuevas, perderas acceso a los cursos premium. Tu progreso se mantendra guardado para cuando regreses.')
            ->action('Renovar Suscripcion', url('/pricing'))
            ->salutation('No pierdas tu racha, **Equipo AMI**');
    }
}
