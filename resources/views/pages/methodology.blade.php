<x-layouts.app>
    <x-slot:title>Metodología</x-slot:title>

    {{-- Hero --}}
    <section class="pt-32 pb-16 relative">
        <div class="absolute top-1/3 -right-32 w-96 h-96 bg-ami-500/5 rounded-full blur-[128px]"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <span class="text-ami-400 text-sm font-semibold uppercase tracking-wider">Metodología</span>
                <h1 class="mt-4 text-4xl sm:text-5xl font-bold text-white leading-tight">
                    Cómo te formamos como<br><span class="text-ami-400">trader profesional</span>
                </h1>
                <p class="mt-6 text-lg text-surface-300 leading-relaxed">
                    Nuestra metodología está diseñada para llevarte desde cero hasta operar con la mentalidad y herramientas
                    de un trader institucional.
                </p>
            </div>
        </div>
    </section>

    {{-- Steps --}}
    <section class="py-20 bg-surface-900/50 border-y border-surface-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-0">
                @foreach([
                    ['step' => '01', 'title' => 'Fundamentos del Mercado', 'desc' => 'Entiendes cómo funcionan los mercados financieros desde adentro. Estructura de mercado, liquidez, order flow y price action institucional.', 'color' => 'ami-400'],
                    ['step' => '02', 'title' => 'Desarrollo de Estrategia', 'desc' => 'Construyes tu propia estrategia basada en principios institucionales. No copiamos setups — te enseñamos a pensar como un trader profesional.', 'color' => 'ami-300'],
                    ['step' => '03', 'title' => 'Gestión de Riesgo', 'desc' => 'Aprendes a proteger tu capital antes de buscar rentabilidad. Position sizing, drawdown management y psicología del trading.', 'color' => 'bullish'],
                    ['step' => '04', 'title' => 'Práctica Supervisada', 'desc' => 'Operas en mercados reales con seguimiento. Tu Trading Journal registra cada operación y te muestra exactamente dónde mejorar.', 'color' => 'bullish-light'],
                    ['step' => '05', 'title' => 'Trader Independiente', 'desc' => 'Operas por tu cuenta con confianza. Tienes metodología, disciplina, herramientas y una comunidad que te respalda.', 'color' => 'ami-400'],
                ] as $item)
                <div class="group flex gap-8 py-10 border-b border-surface-800/50 last:border-0 hover:bg-surface-800/20 -mx-6 px-6 rounded-xl transition-all duration-300">
                    <div class="shrink-0">
                        <span class="text-5xl font-black text-surface-800 group-hover:text-{{ $item['color'] }}/20 transition-colors duration-300">{{ $item['step'] }}</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white mb-3">{{ $item['title'] }}</h3>
                        <p class="text-surface-400 leading-relaxed max-w-2xl">{{ $item['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
