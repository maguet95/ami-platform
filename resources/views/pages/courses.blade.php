<x-layouts.app>
    <x-slot:title>Cursos</x-slot:title>

    {{-- Hero --}}
    <section class="pt-32 pb-16 relative">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <span class="text-ami-400 text-sm font-semibold uppercase tracking-wider">Formación</span>
                <h1 class="mt-4 text-4xl sm:text-5xl font-bold text-white leading-tight">
                    Nuestros <span class="text-ami-400">Cursos</span>
                </h1>
                <p class="mt-6 text-lg text-surface-300 leading-relaxed">
                    Programas diseñados para cada nivel. Desde los fundamentos hasta estrategias avanzadas de trading institucional.
                </p>
            </div>
        </div>
    </section>

    {{-- Courses Grid (placeholder - will be dynamic in Phase 2) --}}
    <section class="pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['title' => 'Trading desde Cero', 'level' => 'Principiante', 'lessons' => 24, 'desc' => 'Fundamentos del mercado, tipos de análisis, y tus primeras operaciones con gestión de riesgo.', 'color' => 'ami'],
                    ['title' => 'Price Action Institucional', 'level' => 'Intermedio', 'lessons' => 36, 'desc' => 'Estructura de mercado, order blocks, liquidez y cómo operan las instituciones financieras.', 'color' => 'ami'],
                    ['title' => 'Trading Avanzado', 'level' => 'Avanzado', 'lessons' => 42, 'desc' => 'Estrategias de alta probabilidad, multi-timeframe analysis y optimización de tu operativa.', 'color' => 'bullish'],
                ] as $course)
                <div class="group bg-surface-800/40 border border-surface-700/50 rounded-2xl overflow-hidden hover:border-ami-500/30 transition-all duration-300 hover:-translate-y-1">
                    {{-- Course Image Placeholder --}}
                    <div class="h-48 bg-gradient-to-br from-surface-700 to-surface-800 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-ami-500/5 to-transparent"></div>
                        <img src="{{ asset('images/logos/isotipo.jpg') }}" alt="AMI" class="h-16 opacity-20 rounded">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-3 py-1 text-xs font-medium bg-{{ $course['color'] }}-500/10 text-{{ $course['color'] }}-400 rounded-full border border-{{ $course['color'] }}-500/20">
                                {{ $course['level'] }}
                            </span>
                            <span class="text-xs text-surface-500">{{ $course['lessons'] }} lecciones</span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2 group-hover:text-ami-300 transition-colors">{{ $course['title'] }}</h3>
                        <p class="text-sm text-surface-400 leading-relaxed mb-6">{{ $course['desc'] }}</p>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-2 text-sm font-medium text-ami-400 hover:text-ami-300 transition-colors">
                            Ver detalles
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Coming Soon Note --}}
            <div class="mt-12 text-center">
                <p class="text-surface-500 text-sm">Más cursos próximamente. <a href="{{ route('register') }}" class="text-ami-400 hover:text-ami-300">Regístrate</a> para ser el primero en enterarte.</p>
            </div>
        </div>
    </section>
</x-layouts.app>
