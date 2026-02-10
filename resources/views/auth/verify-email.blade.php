<x-guest-layout>
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-ami-500/10 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-ami-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-white">Verifica tu Email</h2>
        <p class="mt-2 text-sm text-surface-400 leading-relaxed">
            ¡Gracias por registrarte! Antes de comenzar, verifica tu dirección de correo haciendo clic en el enlace que te acabamos de enviar.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 bg-bullish/10 border border-bullish/20 rounded-xl text-sm text-bullish">
            Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
        </div>
    @endif

    <div class="flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full py-3.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-300 shadow-lg shadow-ami-500/25 hover:shadow-ami-500/40">
                Reenviar Email de Verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full py-3 text-sm text-surface-400 hover:text-white transition-colors">
                Cerrar Sesión
            </button>
        </form>
    </div>
</x-guest-layout>
