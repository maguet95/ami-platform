<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = Plan::active()->orderBy('sort_order')->get();

        return view('pages.pricing', compact('plans'));
    }

    public function checkout(Plan $plan)
    {
        $user = Auth::user();

        if ($user->hasActiveSubscription()) {
            return redirect()->route('dashboard')
                ->with('info', 'Ya tienes una suscripción activa.');
        }

        if (! $plan->stripe_price_id) {
            return redirect()->route('pricing')
                ->with('error', 'Este plan aún no está configurado para pagos.');
        }

        return $user->newSubscription('default', $plan->stripe_price_id)
            ->checkout([
                'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('pricing'),
            ]);
    }

    public function success(Request $request)
    {
        return view('subscription.success');
    }

    public function portal(Request $request)
    {
        return $request->user()->redirectToBillingPortal(route('dashboard'));
    }
}
