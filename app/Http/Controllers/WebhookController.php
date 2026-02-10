<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\User;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class WebhookController extends CashierWebhookController
{
    protected function handleCustomerSubscriptionDeleted(array $payload): void
    {
        parent::handleCustomerSubscriptionDeleted($payload);

        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;

        if (! $stripeCustomerId) {
            return;
        }

        $user = User::where('stripe_id', $stripeCustomerId)->first();

        if (! $user) {
            return;
        }

        // Expire premium enrollments but preserve progress
        $user->enrollments()
            ->active()
            ->whereHas('course', fn ($q) => $q->where('is_free', false))
            ->update([
                'status' => 'expired',
                'expires_at' => now(),
            ]);
    }

    protected function handleInvoicePaymentFailed(array $payload): void
    {
        // Placeholder for future email/notification
    }
}
