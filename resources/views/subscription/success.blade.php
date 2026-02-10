<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Suscripción Exitosa</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto text-center py-12">
        {{-- Success Icon --}}
        <div class="w-20 h-20 bg-bullish/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-bullish" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h3 class="text-2xl font-bold text-white mb-3">
            ¡Bienvenido a AMI Premium!
        </h3>
        <p class="text-surface-400 mb-8 leading-relaxed">
            Tu suscripción ha sido activada exitosamente. Ahora tienes acceso completo a todos los cursos premium de la plataforma.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('courses') }}"
               class="px-6 py-3 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-200 shadow-lg shadow-ami-500/25">
                Explorar Cursos
            </a>
            <a href="{{ route('dashboard') }}"
               class="px-6 py-3 text-sm font-semibold text-surface-300 bg-surface-800 hover:bg-surface-700 border border-surface-700 rounded-xl transition-all duration-200">
                Ir al Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
