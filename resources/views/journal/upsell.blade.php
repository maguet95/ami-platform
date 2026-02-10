<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Trading Journal</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto text-center py-12">
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-8">
            <div class="w-16 h-16 rounded-2xl bg-ami-500/10 flex items-center justify-center mx-auto">
                <svg class="w-8 h-8 text-ami-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            <h3 class="mt-6 text-2xl font-bold text-white">Desbloquea tu Trading Journal</h3>
            <p class="mt-3 text-surface-400 leading-relaxed">
                El Trading Journal es una herramienta premium que te permite analizar tus operaciones automaticamente.
                Visualiza tu historial, estadisticas y rendimiento en un solo lugar.
            </p>

            <ul class="mt-6 text-left max-w-sm mx-auto space-y-2">
                <li class="flex items-center gap-2 text-sm text-surface-300">
                    <svg class="w-4 h-4 text-bullish shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Importacion automatica de trades
                </li>
                <li class="flex items-center gap-2 text-sm text-surface-300">
                    <svg class="w-4 h-4 text-bullish shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Win rate, PnL, drawdown y mas metricas
                </li>
                <li class="flex items-center gap-2 text-sm text-surface-300">
                    <svg class="w-4 h-4 text-bullish shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Filtros por par, direccion y fecha
                </li>
                <li class="flex items-center gap-2 text-sm text-surface-300">
                    <svg class="w-4 h-4 text-bullish shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Resumenes semanales y mensuales
                </li>
            </ul>

            <a href="{{ route('pricing') }}" class="mt-8 inline-flex items-center gap-2 px-6 py-3 bg-ami-500 hover:bg-ami-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-ami-500/25">
                Ver planes
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </div>
</x-app-layout>
