<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ?Carbon $endsAt = null,
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
        $message = (new MailMessage)
            ->subject('Tu suscripcion ha sido cancelada')
            ->greeting("Hola {$notifiable->name},")
            ->line('Tu suscripcion en AMI ha sido cancelada.');

        if ($this->endsAt) {
            $message->line("Tendras acceso a los cursos premium hasta el **{$this->endsAt->format('d/m/Y')}**.");
        }

        return $message
            ->line('Tu progreso queda guardado. Puedes volver en cualquier momento.')
            ->action('Reactivar Suscripcion', url('/pricing'))
            ->salutation('Te esperamos de vuelta, **Equipo AMI**');
    }
}
