<x-layouts.app>
    <x-slot:title>Pago Crypto — Esperando confirmación</x-slot:title>

    <section class="min-h-screen pt-24 pb-16" x-data="cryptoWaiting('{{ $cryptoPayment->order_id }}')">
        <div class="max-w-xl mx-auto px-4">

            {{-- Step Progress --}}
            <div class="mb-10">
                <div class="flex items-center justify-between relative">
                    {{-- Line behind steps --}}
                    <div class="absolute left-0 right-0 top-5 h-px bg-surface-700/60 z-0 mx-10"></div>
                    <div class="absolute left-0 top-5 h-px bg-ami-500 z-0 mx-10 transition-all duration-700" style="right: 33.33%"></div>

                    @php
                        $steps = [
                            ['icon' => '✓', 'label' => 'Cuenta', 'done' => true],
                            ['icon' => '✓', 'label' => 'Plan', 'done' => true],
                            ['icon' => '3', 'label' => 'Pago', 'done' => false, 'current' => true],
                            ['icon' => '4', 'label' => 'Acceso', 'done' => false],
                        ];
                    @endphp

                    @foreach($steps as $step)
                    <div class="flex flex-col items-center gap-2 z-10">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300
                            {{ $step['done'] ?? false
                                ? 'bg-bullish text-white shadow-lg shadow-bullish/30'
                                : (($step['current'] ?? false)
                                    ? 'bg-ami-500 text-white shadow-lg shadow-ami-500/40 ring-4 ring-ami-500/20'
                                    : 'bg-surface-800 border border-surface-700 text-surface-500') }}">
                            {{ $step['done'] ?? false ? '✓' : $step['icon'] }}
                        </div>
                        <span class="text-xs font-medium
                            {{ $step['done'] ?? false ? 'text-bullish' : (($step['current'] ?? false) ? 'text-ami-400' : 'text-surface-500') }}">
                            {{ $step['label'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Main Card --}}
            <div class="bg-surface-900/80 border border-surface-700/40 rounded-3xl overflow-hidden shadow-2xl shadow-black/40">

                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-ami-500/10 to-ami-700/5 border-b border-surface-700/40 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-ami-400 uppercase tracking-widest mb-1">Plan {{ $cryptoPayment->plan->name }}</p>
                            <p class="text-2xl font-bold text-white" style="font-variant-numeric: tabular-nums">
                                ${{ number_format($cryptoPayment->price_amount, 2) }}
                                <span class="text-surface-400 text-base font-normal">USD</span>
                            </p>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-surface-800/60 rounded-full border border-surface-700/50">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            <span class="text-xs text-surface-300 font-medium">Esperando pago</span>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Amount to send --}}
                    <div class="text-center bg-surface-800/50 rounded-2xl p-5 border border-surface-700/30">
                        <p class="text-xs text-surface-400 uppercase tracking-wider mb-3">Monto exacto a enviar</p>
                        @if($cryptoPayment->pay_amount)
                            <p class="text-4xl font-bold text-white leading-none" style="font-variant-numeric: tabular-nums; letter-spacing: -0.02em">
                                {{ $cryptoPayment->pay_amount }}
                            </p>
                            <p class="text-ami-400 font-semibold mt-1 tracking-wide">{{ strtoupper($cryptoPayment->getCurrencyLabel()) }}</p>
                        @else
                            <div class="flex items-center justify-center gap-2 text-surface-400">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Calculando monto…
                            </div>
                        @endif
                        <div class="flex items-center justify-center gap-1.5 mt-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-surface-500"></span>
                            <span class="text-xs text-surface-500">Red TRC20 (TRON)</span>
                        </div>
                    </div>

                    {{-- QR Code --}}
                    <div class="flex justify-center">
                        <div class="relative">
                            <div class="absolute inset-0 bg-ami-500/10 rounded-2xl blur-xl"></div>
                            <div class="relative bg-white p-4 rounded-2xl shadow-xl">
                                <img
                                    src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=0&data={{ urlencode($cryptoPayment->pay_address) }}"
                                    alt="QR de pago"
                                    class="w-48 h-48 rounded"
                                />
                            </div>
                        </div>
                    </div>

                    {{-- Wallet Address --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-surface-400">Dirección de billetera</p>
                            <span class="text-xs text-surface-500 bg-surface-800 px-2 py-0.5 rounded-full">USDT · TRC20</span>
                        </div>
                        <div class="bg-surface-800/60 border border-surface-700/40 rounded-xl p-3.5 mb-3">
                            <code class="text-xs text-surface-300 break-all font-mono leading-relaxed select-all block">{{ $cryptoPayment->pay_address }}</code>
                        </div>
                        {{-- Copy button --}}
                        <button
                            @click="copyAddress('{{ $cryptoPayment->pay_address }}')"
                            class="w-full flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl text-sm font-semibold bg-ami-500/20 border border-ami-500/40 text-ami-300 hover:bg-ami-500/30 transition-all duration-200"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                            </svg>
                            <span x-text="copied ? '✓ ¡Dirección copiada!' : 'Copiar dirección'">Copiar dirección</span>
                        </button>
                    </div>

                    {{-- Status: waiting (siempre visible por defecto) --}}
                    <div x-show="!completed"
                         class="rounded-xl border border-surface-700/40 bg-surface-800/30 px-4 py-3.5 text-sm text-center text-surface-400">
                        <div class="flex items-center justify-center gap-2.5">
                            <svg class="w-4 h-4 animate-spin shrink-0" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Verificando confirmaciones en la red TRON…
                        </div>
                    </div>
                    {{-- Status: completed (oculto por defecto, Alpine lo muestra) --}}
                    <div style="display:none" x-show="completed"
                         class="rounded-xl border border-bullish/30 bg-bullish/5 px-4 py-3.5 text-sm text-center text-bullish font-semibold">
                        <div class="flex items-center justify-center gap-2.5">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            ¡Pago confirmado! Activando acceso…
                        </div>
                    </div>

                    {{-- Tips acordeón (colapsado por defecto) --}}
                    <div x-data="{ open: false }" class="border border-surface-700/30 rounded-xl overflow-hidden">
                        <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 text-xs text-surface-400 hover:text-surface-300 transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                                </svg>
                                ¿Cómo hacer el pago?
                            </span>
                            <svg :class="open && 'rotate-180'" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="border-t border-surface-700/30 px-4 py-4 space-y-2.5">
                            @foreach([
                                ['①', 'Abre tu billetera (Binance, Trust Wallet, etc.)'],
                                ['②', 'Selecciona Enviar → USDT → red TRC20'],
                                ['③', 'Escanea el QR o pega la dirección exacta'],
                                ['④', 'Ingresa el monto exacto que aparece arriba'],
                                ['⑤', 'Confirma el envío — esta página se actualizará sola'],
                            ] as [$num, $tip])
                            <div class="flex items-start gap-3 text-xs text-surface-400">
                                <span class="text-ami-400 font-bold shrink-0">{{ $num }}</span>
                                <span>{{ $tip }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- Card Footer --}}
                <div class="border-t border-surface-700/40 px-6 py-4">
                    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-1 text-xs text-surface-500">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            1–5 min de confirmación
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Esta página se actualiza sola
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                            Pago seguro y verificado
                        </span>
                    </div>
                </div>
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
