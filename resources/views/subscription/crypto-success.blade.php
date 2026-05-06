<x-layouts.app>
    <x-slot:title>¡Pago confirmado!</x-slot:title>

    <section class="min-h-screen pt-28 pb-16 flex items-center justify-center">
        <div class="max-w-md w-full mx-auto px-4 text-center">

            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-bullish/10 border border-bullish/20 mb-6">
                <svg class="w-10 h-10 text-bullish" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-white mb-3">¡Bienvenido a AMI Premium!</h1>
            <p class="text-surface-400 leading-relaxed mb-8">
                Tu pago fue confirmado. Ya tienes acceso completo a todos los cursos, clases en vivo y el trading journal.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('platform.courses') }}"
                   class="px-6 py-3 bg-ami-500 hover:bg-ami-600 text-white font-semibold rounded-xl transition-colors">
                    Ver cursos
                </a>
                <a href="{{ route('dashboard') }}"
                   class="px-6 py-3 bg-surface-700 hover:bg-surface-600 text-white font-semibold rounded-xl transition-colors">
                    Ir al dashboard
                </a>
            </div>

        </div>
    </section>
</x-layouts.app>
