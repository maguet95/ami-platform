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

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
