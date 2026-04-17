<?php

namespace App\Livewire\Subscription;

use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Checkout extends Component
{
    public string $cardNumber = '';

    public string $cardExpiry = '';

    public string $cardCvc = '';

    public string $cardName = '';

    public float $price;

    public bool $alreadySubscribed = false;

    public function mount(): void
    {
        $this->price = (float) config('streamvault.subscription_price', 7.99);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isSubscribed()) {
            $this->alreadySubscribed = true;
        }
    }

    /**
     * Procesa el pago mock y activa la suscripción.
     */
    public function subscribe(): void
    {
        $this->validate([
            'cardNumber' => ['required', 'string', 'size:19'],   // 1234 5678 9012 3456
            'cardExpiry' => ['required', 'string', 'regex:/^\d{2}\/\d{2}$/'],
            'cardCvc'    => ['required', 'string', 'size:3'],
            'cardName'   => ['required', 'string', 'min:3'],
        ], [
            'cardNumber.size'    => __('Enter a valid 16-digit card number.'),
            'cardExpiry.regex'   => __('Use MM/YY format.'),
            'cardCvc.size'       => __('CVC must be 3 digits.'),
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        Subscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'provider'                 => config('streamvault.payment_provider', 'stripe'),
                'provider_subscription_id' => 'mock_' . uniqid(),
                'status'                   => 'active',
                'plan'                     => 'premium',
                'starts_at'                => now(),
                'ends_at'                  => now()->addMonth(),
            ]
        );

        session()->flash('status', __('Subscription activated successfully!'));

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.subscription.checkout')
            ->layout('layouts.auth', ['title' => __('Subscribe')]);
    }
}
