<?php

namespace App\Http\Controllers;

use App\Models\CryptoPayment;
use App\Models\Plan;
use App\Services\NowPaymentsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CryptoCheckoutController extends Controller
{
    public function __construct(private NowPaymentsService $nowPayments) {}

    public function checkout(Plan $plan)
    {
        $user = Auth::user();

        if ($user->hasPremiumAccess()) {
            return redirect()->route('dashboard')
                ->with('info', 'Ya tienes acceso premium activo.');
        }

        try {
            $cryptoPayment = $this->nowPayments->createPayment($user, $plan);

            return redirect()->route('crypto.waiting', $cryptoPayment->order_id);
        } catch (\RuntimeException $e) {
            return redirect()->route('pricing')
                ->with('error', $e->getMessage());
        }
    }

    public function waiting(string $orderId)
    {
        $user = Auth::user();

        $cryptoPayment = CryptoPayment::where('order_id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($cryptoPayment->isCompleted()) {
            return redirect()->route('crypto.success');
        }

        return view('subscription.crypto-waiting', compact('cryptoPayment'));
    }

    public function status(string $orderId)
    {
        $user = Auth::user();

        $cryptoPayment = CryptoPayment::where('order_id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($cryptoPayment->isPending()) {
            $data = $this->nowPayments->getPaymentStatus($cryptoPayment->now_payment_id);
            $newStatus = $data['payment_status'] ?? $cryptoPayment->status;

            if ($newStatus !== $cryptoPayment->status) {
                $cryptoPayment->update(['status' => $newStatus]);

                if ($newStatus === CryptoPayment::STATUS_FINISHED && ! $cryptoPayment->access_grant_id) {
                    $this->nowPayments->handleIpnPayload($data);
                }
            }
        }

        return response()->json([
            'status' => $cryptoPayment->fresh()->status,
            'completed' => $cryptoPayment->fresh()->isCompleted(),
        ]);
    }

    public function success()
    {
        return view('subscription.crypto-success');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('x-nowpayments-sig', '');

        if (! $this->nowPayments->verifyIpnSignature($payload, $signature)) {
            Log::warning('NOWPayments IPN: invalid signature');

            return response('Unauthorized', 401);
        }

        $data = $request->json()->all();

        try {
            $this->nowPayments->handleIpnPayload($data);
        } catch (\Throwable $e) {
            Log::error('NOWPayments IPN: processing error', ['error' => $e->getMessage()]);

            return response('Error', 500);
        }

        return response('OK', 200);
    }
}
