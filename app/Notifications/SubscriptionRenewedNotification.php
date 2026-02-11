<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionRenewedNotification extends Notification implements ShouldQueue
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
            ->subject('Tu suscripcion ha sido renovada')
            ->greeting("Hola {$notifiable->name},")
            ->line("Tu suscripcion **{$this->planName}** ha sido renovada exitosamente.")
            ->line("**Monto cobrado:** \${$this->amount} USD")
            ->line('Sigue disfrutando de todos los cursos premium y herramientas avanzadas de AMI.')
            ->action('Ir a Mis Cursos', url('/student/courses'))
            ->salutation('Gracias por tu confianza, **Equipo AMI**');
    }
}
