<?php

namespace App\Http\Controllers;

use App\Events\PaymentFailed;
use App\Events\SubscriptionCancelled;
use App\Events\SubscriptionConfirmed;
use App\Events\SubscriptionRenewed;
use App\Models\Enrollment;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class WebhookController extends CashierWebhookController
{
    protected function handleCustomerSubscriptionCreated(array $payload): void
    {
        parent::handleCustomerSubscriptionCreated($payload);

        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;

        if (! $stripeCustomerId) {
            return;
        }

        $user = User::where('stripe_id', $stripeCustomerId)->first();

        if (! $user) {
            return;
        }

        $stripePriceId = $payload['data']['object']['items']['data'][0]['price']['id'] ?? null;
        $plan = $stripePriceId ? Plan::where('stripe_price_id', $stripePriceId)->first() : null;
        $planName = $plan?->name ?? 'Premium';
        $amount = number_format(($payload['data']['object']['items']['data'][0]['price']['unit_amount'] ?? 0) / 100, 2);

        SubscriptionConfirmed::dispatch($user, $planName, $amount);
    }

    protected function handleInvoicePaymentSucceeded(array $payload): void
    {
        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;

        if (! $stripeCustomerId) {
            return;
        }

        $user = User::where('stripe_id', $stripeCustomerId)->first();

        if (! $user) {
            return;
        }

        // Only notify on renewals (not first payment)
        $billingReason = $payload['data']['object']['billing_reason'] ?? '';

        if ($billingReason !== 'subscription_cycle') {
            return;
        }

        $stripePriceId = $payload['data']['object']['lines']['data'][0]['price']['id'] ?? null;
        $plan = $stripePriceId ? Plan::where('stripe_price_id', $stripePriceId)->first() : null;
        $planName = $plan?->name ?? 'Premium';
        $amount = number_format(($payload['data']['object']['amount_paid'] ?? 0) / 100, 2);

        SubscriptionRenewed::dispatch($user, $planName, $amount);
    }

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

        $endsAt = isset($payload['data']['object']['current_period_end'])
            ? Carbon::createFromTimestamp($payload['data']['object']['current_period_end'])
            : null;

        SubscriptionCancelled::dispatch($user, $endsAt);
    }

    protected function handleInvoicePaymentFailed(array $payload): void
    {
        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;

        if (! $stripeCustomerId) {
            return;
        }

        $user = User::where('stripe_id', $stripeCustomerId)->first();

        if (! $user) {
            return;
        }

        PaymentFailed::dispatch($user);
    }
}
