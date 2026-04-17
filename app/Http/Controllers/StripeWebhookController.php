<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Event;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('cashier.webhook.secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            Log::warning('Stripe webhook inválido: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        match ($event->type) {
            'invoice.paid'                   => $this->handleInvoicePaid($event),
            'customer.subscription.deleted'  => $this->handleSubscriptionDeleted($event),
            'customer.subscription.updated'  => $this->handleSubscriptionUpdated($event),
            default                          => null,
        };

        return response()->json(['status' => 'ok']);
    }

    private function handleInvoicePaid(Event $event): void
    {
        $stripeSubId = $event->data->object->subscription;

        Subscription::where('provider_subscription_id', $stripeSubId)
            ->update([
                'status'    => 'active',
                'ends_at'   => null,
            ]);
    }

    private function handleSubscriptionDeleted(Event $event): void
    {
        $stripeSubId = $event->data->object->id;

        Subscription::where('provider_subscription_id', $stripeSubId)
            ->update(['status' => 'cancelled']);
    }

    private function handleSubscriptionUpdated(Event $event): void
    {
        $stripeObj = $event->data->object;
        $stripeSubId = $stripeObj->id;

        $statusMap = [
            'active'   => 'active',
            'past_due' => 'past_due',
            'canceled' => 'cancelled',
            'unpaid'   => 'inactive',
        ];

        $newStatus = $statusMap[$stripeObj->status] ?? 'inactive';

        Subscription::where('provider_subscription_id', $stripeSubId)
            ->update(['status' => $newStatus]);
    }
}
