<?php

namespace App\Notifications;

use App\Models\AccessGrant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccessGrantInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public AccessGrant $grant,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $duration = AccessGrant::durationOptions()[$this->grant->duration_type] ?? $this->grant->duration_type;
        $registerUrl = url('/register?invitation=' . $this->grant->token);

        return (new MailMessage)
            ->subject('Has recibido acceso premium a AMI')
            ->greeting('Hola,')
            ->line('Se te ha otorgado **acceso premium** a **AMI — Alpha Markets Institute**.')
            ->line("**Duración:** {$duration}")
            ->line('Para activar tu acceso, regístrate usando el siguiente enlace:')
            ->action('Crear mi cuenta', $registerUrl)
            ->line('Tu acceso se activará automáticamente al completar el registro.')
            ->salutation('**Equipo AMI**');
    }
}
