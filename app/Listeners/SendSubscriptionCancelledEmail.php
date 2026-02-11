<?php

namespace App\Listeners;

use App\Events\SubscriptionCancelled;
use App\Notifications\SubscriptionCancelledNotification;

class SendSubscriptionCancelledEmail
{
    public function handle(SubscriptionCancelled $event): void
    {
        $event->user->notify(new SubscriptionCancelledNotification($event->endsAt));
    }
}
