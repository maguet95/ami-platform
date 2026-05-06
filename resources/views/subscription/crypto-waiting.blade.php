<x-layouts.app>
    <x-slot:title>Pago Crypto — Esperando confirmación</x-slot:title>

    <section class="min-h-screen pt-28 pb-16 flex items-center justify-center">
        <div class="max-w-lg w-full mx-auto px-4" x-data="cryptoWaiting('{{ $cryptoPayment->order_id }}')">

            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-ami-500/10 border border-ami-500/20 mb-4">
                    <svg class="w-7 h-7 text-ami-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">Esperando tu pago</h1>
                <p class="mt-2 text-surface-400 text-sm">
                    Plan <span class="text-white font-medium">{{ $cryptoPayment->plan->name }}</span>
                    — ${{ number_format($cryptoPayment->price_amount, 2) }} USD
                </p>
            </div>

            {{-- Payment Card --}}
            <div class="bg-surface-800/60 border border-surface-700/50 rounded-2xl p-6 space-y-6">

                {{-- Amount --}}
                <div class="text-center bg-surface-900/50 rounded-xl p-4">
                    <p class="text-xs text-surface-400 uppercase tracking-wider mb-1">Monto exacto a enviar</p>
                    @if($cryptoPayment->pay_amount)
                        <p class="text-3xl font-bold text-white font-mono">
                            {{ $cryptoPayment->pay_amount }}
                            <span class="text-ami-400 text-xl">{{ $cryptoPayment->getCurrencyLabel() }}</span>
                        </p>
                    @else
                        <p class="text-surface-400 text-sm">Calculando monto…</p>
                    @endif
                    <p class="text-xs text-surface-500 mt-1">Red: TRC20</p>
                </div>

                {{-- QR Code --}}
                <div class="flex justify-center">
                    <div class="bg-white p-3 rounded-xl">
                        <img
                            src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($cryptoPayment->pay_address) }}"
                            alt="QR de pago"
                            class="w-44 h-44"
                        />
                    </div>
                </div>

                {{-- Address --}}
                <div>
                    <p class="text-xs text-surface-400 mb-2 text-center">Dirección de billetera</p>
                    <div class="flex items-center gap-2 bg-surface-900/70 border border-surface-700/50 rounded-xl p-3">
                        <code class="text-xs text-surface-300 flex-1 break-all font-mono leading-relaxed">
                            {{ $cryptoPayment->pay_address }}
                        </code>
                        <button
                            @click="copyAddress('{{ $cryptoPayment->pay_address }}')"
                            class="flex-shrink-0 p-2 rounded-lg hover:bg-surface-700 text-surface-400 hover:text-white transition-colors"
                            title="Copiar dirección"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                            </svg>
                        </button>
                    </div>
                    <p x-show="copied" x-transition class="text-xs text-bullish text-center mt-2">
                        ✓ Dirección copiada
                    </p>
                </div>

                {{-- Status --}}
                <div class="text-center">
                    <template x-if="!completed">
                        <div class="flex items-center justify-center gap-2 text-surface-400 text-sm">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Verificando pago automáticamente…
                        </div>
                    </template>
                    <template x-if="completed">
                        <div class="flex items-center justify-center gap-2 text-bullish text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            ¡Pago confirmado! Redirigiendo…
                        </div>
                    </template>
                </div>
            </div>

            {{-- Notes --}}
            <div class="mt-6 space-y-2 text-xs text-surface-500 text-center">
                <p>Envía exactamente el monto indicado a la dirección correcta.</p>
                <p>La confirmación puede tardar 1–5 minutos según la red.</p>
                <p>Esta página se actualiza automáticamente.</p>
            </div>

        </div>
    </section>

    @push('scripts')
    <script>
    function cryptoWaiting(orderId) {
        return {
            completed: false,
            copied: false,
            interval: null,

            init() {
                this.interval = setInterval(() => this.checkStatus(), 30000);
            },

            async checkStatus() {
                try {
                    const res = await fetch(`/suscripcion/crypto/${orderId}/estado`);
                    const data = await res.json();

                    if (data.completed) {
                        this.completed = true;
                        clearInterval(this.interval);
                        setTimeout(() => window.location.href = '/suscripcion/crypto-exito', 2000);
                    }
                } catch (e) {}
            },

            copyAddress(address) {
                navigator.clipboard.writeText(address);
                this.copied = true;
                setTimeout(() => this.copied = false, 3000);
            },
        }
    }
    </script>
    @endpush
</x-layouts.app>
