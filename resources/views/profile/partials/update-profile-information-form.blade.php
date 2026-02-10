<section>
    <header>
        <h2 class="text-lg font-semibold text-white">Información del Perfil</h2>
        <p class="mt-1 text-sm text-surface-400">Actualiza tu nombre y dirección de correo electrónico.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-surface-300 mb-2">Nombre</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="w-full px-4 py-3 bg-surface-800/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
            @error('name')
                <p class="mt-1.5 text-sm text-bearish">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-surface-300 mb-2">Correo electrónico</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="w-full px-4 py-3 bg-surface-800/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
            @error('email')
                <p class="mt-1.5 text-sm text-bearish">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-surface-400">
                        Tu dirección de correo no ha sido verificada.
                        <button form="send-verification" class="text-ami-400 hover:text-ami-300 font-medium transition-colors underline">
                            Haz clic aquí para reenviar el email de verificación.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-bullish">Se ha enviado un nuevo enlace de verificación.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-200 shadow-lg shadow-ami-500/25">
                Guardar
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-bullish">Guardado.</p>
            @endif
        </div>
    </form>
</section>
