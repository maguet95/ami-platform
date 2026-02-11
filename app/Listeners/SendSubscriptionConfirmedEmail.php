<?php

namespace App\Listeners;

use App\Events\SubscriptionConfirmed;
use App\Notifications\SubscriptionConfirmedNotification;

class SendSubscriptionConfirmedEmail
{
    public function handle(SubscriptionConfirmed $event): void
    {
        $event->user->notify(new SubscriptionConfirmedNotification($event->planName, $event->amount));
    }
}
