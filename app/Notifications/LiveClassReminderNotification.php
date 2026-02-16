<?php

namespace App\Notifications;

use App\Models\LiveClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LiveClassReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected LiveClass $liveClass,
        protected string $accessToken,
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
        $joinUrl = url("/clase/{$this->liveClass->id}/unirse?token={$this->accessToken}");
        $time = $this->liveClass->starts_at->format('g:i A');

        return (new MailMessage)
            ->subject("Recordatorio: {$this->liveClass->title} comienza pronto")
            ->greeting("Hola {$notifiable->name},")
            ->line("Tu clase en vivo **{$this->liveClass->title}** comienza en unos minutos.")
            ->line("Hora: **{$time}**")
            ->line("Plataforma: {$this->liveClass->getPlatformLabel()}")
            ->action('Unirse Ahora', $joinUrl)
            ->line('Haz clic en el boton para unirte a la clase.')
            ->salutation('Exitos, **Equipo AMI**');
    }
}
