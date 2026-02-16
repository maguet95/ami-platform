<?php

namespace App\Notifications;

use App\Models\LiveClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LiveClassScheduledNotification extends Notification implements ShouldQueue
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
        $date = $this->liveClass->starts_at->translatedFormat('l j \\d\\e F, Y');
        $time = $this->liveClass->starts_at->format('g:i A');

        $message = (new MailMessage)
            ->subject("Nueva clase en vivo: {$this->liveClass->title}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Se ha programado una nueva clase en vivo en **AMI**.")
            ->line("**{$this->liveClass->title}**")
            ->line("Fecha: {$date}")
            ->line("Hora: {$time}")
            ->line("Duracion: {$this->liveClass->duration_minutes} minutos")
            ->line("Plataforma: {$this->liveClass->getPlatformLabel()}");

        if ($this->liveClass->instructor) {
            $message->line("Instructor: {$this->liveClass->instructor->name}");
        }

        if ($this->liveClass->description) {
            $message->line($this->liveClass->description);
        }

        return $message
            ->action('Ver Clase', $joinUrl)
            ->line('Recibiras un recordatorio 15 minutos antes del inicio.')
            ->salutation('Nos vemos en clase, **Equipo AMI**');
    }
}
