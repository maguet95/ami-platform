<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Dashboard</h2>
    </x-slot>

    {{-- Welcome Card --}}
    <div class="mb-8 bg-gradient-to-r from-ami-500/10 to-ami-700/10 border border-ami-500/20 rounded-2xl p-6 lg:p-8">
        <h3 class="text-lg font-semibold text-white">
            ¡Bienvenido de vuelta, {{ Auth::user()->name }}!
        </h3>
        <p class="mt-1 text-sm text-surface-400">Continúa tu formación como trader profesional.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-surface-500 uppercase tracking-wider">Cursos Inscritos</span>
                <div class="w-9 h-9 bg-ami-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-ami-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">0</p>
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-surface-500 uppercase tracking-wider">Lecciones Completadas</span>
                <div class="w-9 h-9 bg-bullish/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-bullish" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">0</p>
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-surface-500 uppercase tracking-wider">Horas de Estudio</span>
                <div class="w-9 h-9 bg-purple-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">0h</p>
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-surface-500 uppercase tracking-wider">Progreso General</span>
                <div class="w-9 h-9 bg-amber-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">0%</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Continue Learning --}}
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6">
            <h3 class="text-base font-semibold text-white mb-4">Continuar Aprendiendo</h3>
            <div class="flex flex-col items-center justify-center py-8 text-center">
                <div class="w-16 h-16 bg-surface-800 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-surface-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" />
                    </svg>
                </div>
                <p class="text-sm text-surface-400 mb-4">Aún no estás inscrito en ningún curso.</p>
                <a href="{{ route('courses') }}"
                   class="px-5 py-2.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-200 shadow-lg shadow-ami-500/25">
                    Explorar Cursos
                </a>
            </div>
        </div>

        {{-- Activity --}}
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6">
            <h3 class="text-base font-semibold text-white mb-4">Actividad Reciente</h3>
            <div class="flex flex-col items-center justify-center py-8 text-center">
                <div class="w-16 h-16 bg-surface-800 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-surface-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm text-surface-400">Tu actividad aparecerá aquí una vez que comiences a estudiar.</p>
            </div>
        </div>
    </div>
</x-app-layout>
