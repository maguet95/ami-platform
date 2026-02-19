<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
            ->subject('Bienvenido a AMI — Tu camino como trader comienza ahora')
            ->greeting("Hola {$notifiable->name},")
            ->line('Tu cuenta en **AMI — Alpha Markets Institute** ha sido verificada exitosamente.')
            ->line('Estamos felices de tenerte. Aqui encontraras cursos disenados para formarte como trader profesional.')
            ->line('Explora nuestros cursos y da el primer paso en tu formacion como trader.')
            ->action('Explorar Cursos', url('/cursos'))
            ->line('Si tienes preguntas, no dudes en contactarnos.')
            ->salutation('Exitos en tu camino, **Equipo AMI**');
    }
}
