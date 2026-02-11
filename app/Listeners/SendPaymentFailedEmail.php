<?php

namespace App\Listeners;

use App\Events\PaymentFailed;
use App\Notifications\PaymentFailedNotification;

class SendPaymentFailedEmail
{
    public function handle(PaymentFailed $event): void
    {
        $event->user->notify(new PaymentFailedNotification);
    }
}
