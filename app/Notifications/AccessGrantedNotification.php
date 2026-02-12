<?php

namespace App\Notifications;

use App\Models\AccessGrant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccessGrantedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public AccessGrant $grant,
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
        $duration = AccessGrant::durationOptions()[$this->grant->duration_type] ?? $this->grant->duration_type;

        $message = (new MailMessage)
            ->subject('Acceso premium activado en AMI')
            ->greeting("Hola {$notifiable->name},")
            ->line('Se te ha otorgado **acceso premium** a **AMI — Alpha Markets Institute**.')
            ->line("**Duración:** {$duration}");

        if ($this->grant->expires_at) {
            $message->line("**Expira:** {$this->grant->expires_at->format('d/m/Y')}");
        } else {
            $message->line('**Expira:** Nunca (acceso de por vida)');
        }

        return $message
            ->line('Ya puedes acceder a todos los cursos premium y al Trading Journal.')
            ->action('Ir a la plataforma', url('/dashboard'))
            ->salutation('**Equipo AMI**');
    }
}
