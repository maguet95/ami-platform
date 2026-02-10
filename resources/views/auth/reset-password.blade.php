<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white">Nueva Contraseña</h2>
        <p class="mt-2 text-sm text-surface-400">Establece una nueva contraseña para tu cuenta</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-medium text-surface-300 mb-2">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                   class="w-full px-4 py-3 bg-surface-800/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
            @error('email')
                <p class="mt-1.5 text-sm text-bearish">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-surface-300 mb-2">Nueva contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full px-4 py-3 bg-surface-800/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
            @error('password')
                <p class="mt-1.5 text-sm text-bearish">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-surface-300 mb-2">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full px-4 py-3 bg-surface-800/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
        </div>

        <button type="submit"
                class="w-full py-3.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-300 shadow-lg shadow-ami-500/25 hover:shadow-ami-500/40">
            Restablecer Contraseña
        </button>
    </form>
</x-guest-layout>
