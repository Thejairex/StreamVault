<?php

namespace App\Livewire\Subscription;

use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Stripe\PaymentIntent;
use Stripe\Stripe as StripeClient;

class Checkout extends Component
{
    public string $cardholderName = '';
    public bool $processing = false;
    public ?string $errorMessage = null;
    public ?string $clientSecret = null;

    public function mount(): void
    {
        if (Auth::user()->isSubscribed()) {
            $this->redirect(route('home'));
        }

        StripeClient::setApiKey(config('cashier.secret'));
        $intent = \Stripe\SetupIntent::create([
            'usage'             => 'off_session',
            'customer'          => $this->getOrCreateStripeCustomer(),
        ]);

        $this->clientSecret = $intent->client_secret;
    }

    public function subscribe(string $paymentMethodId): void
    {
        $this->processing = true;
        $this->errorMessage = null;

        try {
            StripeClient::setApiKey(config('cashier.secret'));

            $user = Auth::user();
            $customerId = $this->getOrCreateStripeCustomer();

            // Adjuntar el payment method al customer
            \Stripe\PaymentMethod::retrieve($paymentMethodId)->attach([
                'customer' => $customerId,
            ]);

            // Crear la suscripción en Stripe
            $stripeSub = \Stripe\Subscription::create([
                'customer'               => $customerId,
                'items'                  => [['price' => config('cashier.prices.premium')]],
                'default_payment_method' => $paymentMethodId,
                'expand'                 => ['latest_invoice.payment_intent'],
            ]);

            // Guardar en nuestra tabla subscriptions
            Subscription::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'provider'                  => 'stripe',
                    'provider_subscription_id'  => $stripeSub->id,
                    'status'                    => $stripeSub->status === 'active' ? 'active' : 'inactive',
                    'plan'                      => 'premium',
                    'starts_at'                 => now(),
                    'ends_at'                   => null,
                ]
            );

            $this->redirect(route('home'));
        } catch (\Exception $e) {
            $this->errorMessage = 'Hubo un error al procesar el pago: ' . $e->getMessage();
            $this->processing = false;
        }
    }

    private function getOrCreateStripeCustomer(): string
    {
        StripeClient::setApiKey(config('cashier.secret'));
        $user = Auth::user();

        // Buscar si ya tiene suscripción con un customer de Stripe
        $sub = Subscription::where('user_id', $user->id)->first();

        if ($sub && $sub->stripe_customer_id) {
            return $sub->stripe_customer_id;
        }

        $customer = \Stripe\Customer::create([
            'email' => $user->email,
            'name'  => $user->name,
        ]);

        return $customer->id;
    }

    public function render()
    {
        return view('livewire.subscription.checkout', [
            'price'     => config('streamvault.subscription_price'),
            'planName'  => 'StreamVault Premium',
        ]);
    }
}
