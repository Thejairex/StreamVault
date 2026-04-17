<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            {{-- ─── Payment Information ───────────────────────────────────── --}}
            <flux:separator text="{{ __('Payment method') }}" />

            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                {{ __('A valid payment method is required to complete your registration. You will be charged $:price/month.', ['price' => number_format(config('streamvault.subscription_price', 7.99), 2)]) }}
            </p>

            <flux:input
                name="card_name"
                :label="__('Name on card')"
                :value="old('card_name')"
                type="text"
                required
                autocomplete="cc-name"
                :placeholder="__('John Doe')"
                data-test="card-name-input"
            />

            <flux:input
                name="card_number"
                :label="__('Card number')"
                :value="old('card_number')"
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
                    name="card_expiry"
                    :label="__('Expiry')"
                    :value="old('card_expiry')"
                    type="text"
                    required
                    autocomplete="cc-exp"
                    placeholder="MM/YY"
                    maxlength="5"
                    x-mask="99/99"
                    data-test="card-expiry-input"
                />

                <flux:input
                    name="card_cvc"
                    :label="__('CVC')"
                    :value="old('card_cvc')"
                    type="text"
                    required
                    autocomplete="cc-csc"
                    placeholder="123"
                    maxlength="3"
                    x-mask="999"
                    data-test="card-cvc-input"
                />
            </div>

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
