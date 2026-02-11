<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Problema con tu pago')
            ->greeting("Hola {$notifiable->name},")
            ->line('Hubo un problema al procesar tu ultimo pago de suscripcion en AMI.')
            ->line('Para evitar la interrupcion de tu acceso a los cursos premium, por favor actualiza tu metodo de pago lo antes posible.')
            ->action('Actualizar Metodo de Pago', url('/billing'))
            ->line('Si crees que esto es un error, contactanos y te ayudaremos.')
            ->line('Este es un correo de seguridad y se envia siempre, independientemente de tus preferencias de notificacion.')
            ->salutation('Equipo de Soporte, **AMI**');
    }
}
