<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white">Iniciar Sesión</h2>
        <p class="mt-2 text-sm text-surface-400">Accede a tu cuenta en AMI</p>
    </div>

    <x-auth-session-status class="mb-4 text-bullish text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-surface-300 mb-2">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full px-4 py-3 bg-surface-800/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
            @error('email')
                <p class="mt-1.5 text-sm text-bearish">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-surface-300 mb-2">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full px-4 py-3 bg-surface-800/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
            @error('password')
                <p class="mt-1.5 text-sm text-bearish">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-surface-600 bg-surface-800 text-ami-500 focus:ring-ami-500/50 focus:ring-offset-0">
                <span class="ms-2 text-sm text-surface-400">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-ami-400 hover:text-ami-300 transition-colors" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full py-3.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-300 shadow-lg shadow-ami-500/25 hover:shadow-ami-500/40">
            Iniciar Sesión
        </button>

        <p class="text-center text-sm text-surface-400">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="text-ami-400 hover:text-ami-300 font-medium transition-colors">Regístrate</a>
        </p>
    </form>
</x-guest-layout>
