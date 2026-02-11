<?php

namespace App\Listeners;

use App\Notifications\WelcomeNotification;
use Illuminate\Auth\Events\Verified;

class SendWelcomeEmail
{
    public function handle(Verified $event): void
    {
        $event->user->notify(new WelcomeNotification);
    }
}
