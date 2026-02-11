<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseEnrolledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Course $course,
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
        $lessonCount = $this->course->getLessonCount();
        $level = $this->course->getLevelLabel();

        return (new MailMessage)
            ->subject("Te has inscrito en: {$this->course->title}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Te has inscrito exitosamente en el curso **{$this->course->title}**.")
            ->line("**Nivel:** {$level} | **Lecciones:** {$lessonCount}")
            ->line('Comienza ahora y avanza a tu ritmo. Recuerda: el proceso es lo que importa.')
            ->action('Comenzar Curso', url("/student/courses/{$this->course->slug}"))
            ->salutation('A por ello, **Equipo AMI**');
    }
}
