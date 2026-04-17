<div class="min-h-screen flex items-center justify-center bg-[#131315]">
    <div class="w-full max-w-md p-8" style="background: #201f21;">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-white font-bold text-2xl tracking-tight" style="font-family: 'Cabinet Grotesk', sans-serif; letter-spacing: -0.03em;">
                StreamVault
            </h1>
            <p class="mt-1" style="color: #cdc2d8; font-size: 0.875rem;">
                Suscripción Premium &mdash; ${{ number_format($price, 2) }}/mes
            </p>
        </div>

        {{-- Error --}}
        @if ($errorMessage)
            <div class="mb-4 p-3 text-sm" style="background: #2a1a1a; color: #ff6b6b;">
                {{ $errorMessage }}
            </div>
        @endif

        {{-- Formulario Stripe --}}
        <form id="payment-form" wire:submit.prevent>
            <div class="mb-4">
                <label class="block mb-1 text-xs uppercase tracking-widest" style="color: #cdc2d8;">Nombre en la tarjeta</label>
                <input
                    type="text"
                    wire:model="cardholderName"
                    placeholder="Juan Pérez"
                    class="w-full px-3 py-2 text-sm outline-none"
                    style="background: #0e0e10; color: #e5e1e4; border-bottom: 1px solid #4b4455;"
                />
            </div>

            <div class="mb-6">
                <label class="block mb-1 text-xs uppercase tracking-widest" style="color: #cdc2d8;">Datos de tarjeta</label>
                <div id="card-element" class="px-3 py-3" style="background: #0e0e10; border-bottom: 1px solid #4b4455;"></div>
            </div>

            <button
                id="submit-btn"
                type="submit"
                class="w-full py-3 text-sm font-bold uppercase tracking-widest"
                style="background: linear-gradient(135deg, #9147ff, #5e00c1); color: #fffcff; cursor: pointer;"
                :disabled="processing"
            >
                <span id="btn-text">Suscribirme por ${{ number_format($price, 2) }}/mes</span>
            </button>
        </form>

        <p class="mt-4 text-center" style="color: #4b4455; font-size: 0.75rem;">
            Pago seguro procesado por Stripe. Podés cancelar cuando quieras.
        </p>
    </div>

    {{-- Stripe.js --}}
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stripe = Stripe('{{ config('cashier.key') }}');
            const elements = stripe.elements({ clientSecret: '{{ $clientSecret }}' });
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        color: '#e5e1e4',
                        fontFamily: 'Inter, sans-serif',
                        fontSize: '14px',
                        '::placeholder': { color: '#4b4455' },
                    },
                    invalid: { color: '#ff6b6b' },
                }
            });
            cardElement.mount('#card-element');

            document.getElementById('payment-form').addEventListener('submit', async (e) => {
                e.preventDefault();
                document.getElementById('btn-text').textContent = 'Procesando...';

                const { paymentMethod, error } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                    billing_details: { name: @this.cardholderName },
                });

                if (error) {
                    @this.set('errorMessage', error.message);
                    document.getElementById('btn-text').textContent = 'Suscribirme por ${{ number_format($price, 2) }}/mes';
                    return;
                }

                @this.subscribe(paymentMethod.id);
            });
        });
    </script>
</div>
