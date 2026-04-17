<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function checkout()
    {
        return view('subscription.checkout-page');
    }

    public function cancel(Request $request)
    {
        $sub = $request->user()->subscription;

        if ($sub && $sub->provider_subscription_id) {
            \Stripe\Stripe::setApiKey(config('cashier.secret'));
            \Stripe\Subscription::update($sub->provider_subscription_id, [
                'cancel_at_period_end' => true,
            ]);

            $sub->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Tu suscripción fue cancelada. Sigue activa hasta fin del período.');
    }
}
