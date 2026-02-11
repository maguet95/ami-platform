<section>
    <header>
        <h2 class="text-lg font-semibold text-white">Preferencias de Correo</h2>
        <p class="mt-1 text-sm text-surface-400">Configura que notificaciones deseas recibir por correo electronico.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div class="space-y-4">
            <label class="flex items-center justify-between p-4 bg-surface-800/60 border border-surface-700 rounded-xl cursor-pointer hover:border-surface-600 transition-all">
                <div>
                    <span class="text-sm font-medium text-white">Recibir notificaciones por correo</span>
                    <p class="text-xs text-surface-400 mt-0.5">Inscripciones, logros y novedades de la plataforma.</p>
                </div>
                <input type="hidden" name="email_notifications" value="0">
                <input type="checkbox" name="email_notifications" value="1"
                       {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}
                       class="w-5 h-5 rounded bg-surface-700 border-surface-600 text-ami-500 focus:ring-ami-500/50 focus:ring-offset-0">
            </label>

            <label class="flex items-center justify-between p-4 bg-surface-800/60 border border-surface-700 rounded-xl cursor-pointer hover:border-surface-600 transition-all">
                <div>
                    <span class="text-sm font-medium text-white">Recibir resumen semanal</span>
                    <p class="text-xs text-surface-400 mt-0.5">Un resumen cada lunes con tu progreso, XP y logros de la semana.</p>
                </div>
                <input type="hidden" name="weekly_digest" value="0">
                <input type="checkbox" name="weekly_digest" value="1"
                       {{ old('weekly_digest', $user->weekly_digest) ? 'checked' : '' }}
                       class="w-5 h-5 rounded bg-surface-700 border-surface-600 text-ami-500 focus:ring-ami-500/50 focus:ring-offset-0">
            </label>
        </div>

        <p class="text-xs text-surface-500">Los correos de seguridad (cambio de contrasena, problemas de pago) se envian siempre.</p>

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
