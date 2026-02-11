<?php

namespace App\Listeners;

use App\Events\SubscriptionRenewed;
use App\Notifications\SubscriptionRenewedNotification;

class SendSubscriptionRenewedEmail
{
    public function handle(SubscriptionRenewed $event): void
    {
        $event->user->notify(new SubscriptionRenewedNotification($event->planName, $event->amount));
    }
}
