<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Mi Perfil</h2>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        {{-- Public Profile Link --}}
        <div class="bg-gradient-to-r from-ami-500/10 to-ami-700/10 border border-ami-500/20 rounded-2xl p-5 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-white">Perfil Publico</p>
                <p class="text-xs text-surface-400">Configura tu perfil visible para la comunidad de traders.</p>
            </div>
            <a href="{{ route('profile.edit-public') }}"
               class="px-4 py-2 text-xs font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-lg transition-all shadow-lg shadow-ami-500/25">
                Configurar
            </a>
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8">
            @include('profile.partials.email-preferences-form')
        </div>

        {{-- Tutorial --}}
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8">
            <h3 class="text-base font-semibold text-white mb-1">Tutorial de la plataforma</h3>
            <p class="text-sm text-surface-400 mb-4">Revisa el tour interactivo para recordar cómo usar cada sección de AMI.</p>
            <form method="POST" action="{{ route('onboarding.reset') }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-ami-400 bg-ami-500/10 hover:bg-ami-500/20 border border-ami-500/20 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                    </svg>
                    Ver tutorial de nuevo
                </button>
            </form>
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
