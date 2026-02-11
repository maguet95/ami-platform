<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu contrasena ha sido actualizada')
            ->greeting("Hola {$notifiable->name},")
            ->line('Te informamos que la contrasena de tu cuenta en AMI ha sido actualizada exitosamente.')
            ->line('Si **no realizaste** este cambio, por favor contactanos de inmediato para proteger tu cuenta.')
            ->action('Ir a Mi Perfil', url('/profile'))
            ->line('Este es un correo de seguridad y se envia siempre, independientemente de tus preferencias de notificacion.')
            ->salutation('Equipo de Seguridad, **AMI**');
    }
}
