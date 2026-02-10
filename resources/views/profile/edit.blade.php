<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Mi Perfil</h2>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
