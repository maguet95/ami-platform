<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white">Crear Cuenta</h2>
        <p class="mt-2 text-sm text-surface-400">Únete a Alpha Markets Institute</p>
    </div>

    <a href="{{ route('auth.google') }}"
       class="w-full flex items-center justify-center gap-3 py-3.5 text-sm font-semibold text-white bg-surface-800/60 border border-surface-700 hover:bg-surface-700/60 rounded-xl transition-all duration-300">
        <svg class="w-5 h-5" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Continuar con Google
    </a>

    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-surface-700"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 text-surface-500 bg-surface-900">o</span>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-surface-300 mb-2">Nombre completo</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="w-full px-4 py-3 bg-surface-800/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
            @error('name')
                <p class="mt-1.5 text-sm text-bearish">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-surface-300 mb-2">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="w-full px-4 py-3 bg-surface-800/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
            @error('email')
                <p class="mt-1.5 text-sm text-bearish">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-surface-300 mb-2">Contraseña</label>
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
            Crear Cuenta
        </button>

        <p class="text-center text-sm text-surface-400">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-ami-400 hover:text-ami-300 font-medium transition-colors">Inicia sesión</a>
        </p>
    </form>
</x-guest-layout>
