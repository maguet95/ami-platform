<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white">Recuperar Contraseña</h2>
        <p class="mt-2 text-sm text-surface-400">Te enviaremos un enlace para restablecer tu contraseña</p>
    </div>

    <x-auth-session-status class="mb-4 text-bullish text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-surface-300 mb-2">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full px-4 py-3 bg-surface-800/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
            @error('email')
                <p class="mt-1.5 text-sm text-bearish">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-3.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-300 shadow-lg shadow-ami-500/25 hover:shadow-ami-500/40">
            Enviar Enlace de Recuperación
        </button>

        <p class="text-center text-sm text-surface-400">
            ¿Recordaste tu contraseña?
            <a href="{{ route('login') }}" class="text-ami-400 hover:text-ami-300 font-medium transition-colors">Inicia sesión</a>
        </p>
    </form>
</x-guest-layout>
