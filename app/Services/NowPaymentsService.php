<?php

namespace App\Services;

use App\Models\AccessGrant;
use App\Models\CryptoPayment;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NowPaymentsService
{
    private string $apiKey;

    private string $ipnSecret;

    private string $baseUrl = 'https://api.nowpayments.io/v1';

    public function __construct()
    {
        $this->apiKey = config('services.nowpayments.api_key');
        $this->ipnSecret = config('services.nowpayments.ipn_secret');
    }

    public function createPayment(User $user, Plan $plan): CryptoPayment
    {
        $orderId = 'ami_'.$user->id.'_'.$plan->slug.'_'.time();
        $durationType = $plan->interval === 'yearly' ? AccessGrant::DURATION_1_YEAR : AccessGrant::DURATION_1_MONTH;
        $payCurrency = config('services.nowpayments.pay_currency', 'usdttrc20');

        $response = Http::withHeaders(['x-api-key' => $this->apiKey])
            ->post($this->baseUrl.'/payment', [
                'price_amount' => (float) $plan->price,
                'price_currency' => 'usd',
                'pay_currency' => $payCurrency,
                'order_id' => $orderId,
                'order_description' => 'AMI Premium — Plan '.$plan->name,
                'ipn_callback_url' => route('webhooks.nowpayments'),
            ]);

        if (! $response->successful()) {
            Log::error('NOWPayments createPayment failed', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'response' => $response->json(),
            ]);
            throw new \RuntimeException('No se pudo crear el pago. Intenta más tarde.');
        }

        $data = $response->json();

        return CryptoPayment::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'now_payment_id' => $data['payment_id'],
            'order_id' => $orderId,
            'status' => $data['payment_status'] ?? 'waiting',
            'price_amount' => $plan->price,
            'price_currency' => 'usd',
            'pay_currency' => $data['pay_currency'],
            'pay_address' => $data['pay_address'],
            'pay_amount' => $data['pay_amount'] ?? null,
            'duration_type' => $durationType,
        ]);
    }

    public function getPaymentStatus(string $nowPaymentId): array
    {
        $response = Http::withHeaders(['x-api-key' => $this->apiKey])
            ->get($this->baseUrl.'/payment/'.$nowPaymentId);

        if (! $response->successful()) {
            return ['status' => 'error'];
        }

        return $response->json();
    }

    public function verifyIpnSignature(string $payload, string $signature): bool
    {
        $expected = hash_hmac('sha512', $payload, $this->ipnSecret);

        return hash_equals($expected, strtolower($signature));
    }

    public function handleIpnPayload(array $data): void
    {
        $nowPaymentId = $data['payment_id'] ?? null;
        $status = $data['payment_status'] ?? null;

        if (! $nowPaymentId || ! $status) {
            return;
        }

        $cryptoPayment = CryptoPayment::where('now_payment_id', $nowPaymentId)->first();

        if (! $cryptoPayment) {
            Log::warning('NOWPayments IPN: payment not found', ['payment_id' => $nowPaymentId]);

            return;
        }

        $cryptoPayment->update([
            'status' => $status,
            'actually_paid' => $data['actually_paid'] ?? null,
        ]);

        if ($status === CryptoPayment::STATUS_FINISHED && ! $cryptoPayment->access_grant_id) {
            $this->activateAccess($cryptoPayment);
        }
    }

    private function activateAccess(CryptoPayment $cryptoPayment): void
    {
        $user = $cryptoPayment->user;

        $grant = AccessGrant::create([
            'email' => $user->email,
            'user_id' => $user->id,
            'granted_by' => $user->id,
            'duration_type' => $cryptoPayment->duration_type,
            'status' => AccessGrant::STATUS_PENDING,
            'token' => AccessGrant::generateToken(),
            'notes' => 'Pago crypto NOWPayments — '.$cryptoPayment->now_payment_id,
        ]);

        $grant->activate($user);

        $cryptoPayment->update(['access_grant_id' => $grant->id]);

        Log::info('NOWPayments: access activated', [
            'user_id' => $user->id,
            'payment_id' => $cryptoPayment->now_payment_id,
            'access_grant_id' => $grant->id,
            'expires_at' => $grant->expires_at,
        ]);

        event(new \App\Events\SubscriptionConfirmed(
            $user,
            $cryptoPayment->plan->name,
            number_format($cryptoPayment->price_amount, 2)
        ));
    }
}
