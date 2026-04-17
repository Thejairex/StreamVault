<div>
    <x-auth-header
        :title="__('Activate your subscription')"
        :description="__('Complete your payment to access StreamVault')"
    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    @if ($alreadySubscribed)
        <div class="flex flex-col items-center gap-4 py-6">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10">
                <flux:icon.check-circle class="size-8 text-emerald-500" />
            </div>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('You already have an active subscription.') }}
            </p>
            <flux:button :href="route('dashboard')" variant="primary" wire:navigate>
                {{ __('Go to Dashboard') }}
            </flux:button>
        </div>
    @else
        {{-- Plan Card --}}
        <div class="rounded-xl border border-indigo-500/30 bg-linear-to-b from-indigo-500/5 to-transparent p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-zinc-900 dark:text-white">Premium</h3>
                    <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Full access to StreamVault') }}</p>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold text-zinc-900 dark:text-white">${{ number_format($price, 2) }}</span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">/{{ __('month') }}</span>
                </div>
            </div>

            <ul class="mt-4 space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                <li class="flex items-center gap-2">
                    <flux:icon.check class="size-4 text-emerald-500" />
                    {{ __('Follow up to :count streamers', ['count' => config('streamvault.max_follows', 5)]) }}
                </li>
                <li class="flex items-center gap-2">
                    <flux:icon.check class="size-4 text-emerald-500" />
                    {{ __('Revenue sharing with your favorites') }}
                </li>
                <li class="flex items-center gap-2">
                    <flux:icon.check class="size-4 text-emerald-500" />
                    {{ __('Exclusive dashboard & analytics') }}
                </li>
            </ul>
        </div>

        {{-- Payment Form --}}
        <form wire:submit="subscribe" class="flex flex-col gap-4">
            <flux:separator text="{{ __('Payment details') }}" />

            <flux:input
                wire:model="cardName"
                :label="__('Name on card')"
                type="text"
                required
                autocomplete="cc-name"
                :placeholder="__('John Doe')"
                data-test="card-name-input"
            />

            <flux:input
                wire:model="cardNumber"
                :label="__('Card number')"
                type="text"
                required
                autocomplete="cc-number"
                placeholder="1234 5678 9012 3456"
                maxlength="19"
                x-mask="9999 9999 9999 9999"
                data-test="card-number-input"
            />

            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    wire:model="cardExpiry"
                    :label="__('Expiry')"
                    type="text"
                    required
                    autocomplete="cc-exp"
                    placeholder="MM/YY"
                    maxlength="5"
                    x-mask="99/99"
                    data-test="card-expiry-input"
                />

                <flux:input
                    wire:model="cardCvc"
                    :label="__('CVC')"
                    type="text"
                    required
                    autocomplete="cc-csc"
                    placeholder="123"
                    maxlength="3"
                    x-mask="999"
                    data-test="card-cvc-input"
                />
            </div>

            <flux:button type="submit" variant="primary" class="w-full" data-test="subscribe-button">
                {{ __('Subscribe — $:price/mo', ['price' => number_format($price, 2)]) }}
            </flux:button>
        </form>

        <p class="text-center text-xs text-zinc-500 dark:text-zinc-500">
            {{ __('Your card will be charged :price/month. Cancel anytime.', ['price' => '$' . number_format($price, 2)]) }}
        </p>
    @endif
</div>
