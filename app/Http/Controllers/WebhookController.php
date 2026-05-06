<?php

namespace App\Http\Controllers;

use App\Events\PaymentFailed;
use App\Events\SubscriptionCancelled;
use App\Events\SubscriptionConfirmed;
use App\Events\SubscriptionRenewed;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends CashierWebhookController
{
    protected function handleCustomerSubscriptionCreated(array $payload): Response
    {
        $response = parent::handleCustomerSubscriptionCreated($payload);

        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;

        if ($stripeCustomerId) {
            $user = User::where('stripe_id', $stripeCustomerId)->first();

            if ($user) {
                $stripePriceId = $payload['data']['object']['items']['data'][0]['price']['id'] ?? null;
                $plan = $stripePriceId ? Plan::where('stripe_price_id', $stripePriceId)->first() : null;
                $planName = $plan->name ?? 'Premium';
                $amount = number_format(($payload['data']['object']['items']['data'][0]['price']['unit_amount'] ?? 0) / 100, 2);

                SubscriptionConfirmed::dispatch($user, $planName, $amount);
            }
        }

        return $response;
    }

    protected function handleInvoicePaymentSucceeded(array $payload): Response
    {
        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;

        if ($stripeCustomerId) {
            $user = User::where('stripe_id', $stripeCustomerId)->first();

            if ($user) {
                $billingReason = $payload['data']['object']['billing_reason'] ?? '';

                if ($billingReason === 'subscription_cycle') {
                    $stripePriceId = $payload['data']['object']['lines']['data'][0]['price']['id'] ?? null;
                    $plan = $stripePriceId ? Plan::where('stripe_price_id', $stripePriceId)->first() : null;
                    $planName = $plan->name ?? 'Premium';
                    $amount = number_format(($payload['data']['object']['amount_paid'] ?? 0) / 100, 2);

                    SubscriptionRenewed::dispatch($user, $planName, $amount);
                }
            }
        }

        return $this->successMethod();
    }

    protected function handleCustomerSubscriptionDeleted(array $payload): Response
    {
        $response = parent::handleCustomerSubscriptionDeleted($payload);

        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;

        if ($stripeCustomerId) {
            $user = User::where('stripe_id', $stripeCustomerId)->first();

            if ($user) {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query = $user->enrollments()->where('status', 'active');
                $query->whereHas('course', fn ($q) => $q->whereIn('access_type', ['premium', 'exclusive']))
                    ->update([
                        'status' => 'expired',
                        'expires_at' => now(),
                    ]);

                $endsAt = isset($payload['data']['object']['current_period_end'])
                    ? Carbon::createFromTimestamp($payload['data']['object']['current_period_end'])
                    : null;

                SubscriptionCancelled::dispatch($user, $endsAt);
            }
        }

        return $response;
    }

    protected function handleInvoicePaymentFailed(array $payload): Response
    {
        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;

        if ($stripeCustomerId) {
            $user = User::where('stripe_id', $stripeCustomerId)->first();

            if ($user) {
                PaymentFailed::dispatch($user);
            }
        }

        return $this->successMethod();
    }
}
