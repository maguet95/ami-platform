<?php

namespace App\Listeners;

use App\Events\CourseEnrolled;
use App\Notifications\CourseEnrolledNotification;

class SendCourseEnrollmentEmail
{
    public function handle(CourseEnrolled $event): void
    {
        $event->user->notify(new CourseEnrolledNotification($event->course));
    }
}
