<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-bearish">Eliminar Cuenta</h2>
        <p class="mt-1 text-sm text-surface-400">
            Una vez eliminada tu cuenta, todos sus recursos y datos serán eliminados permanentemente. Descarga cualquier información que desees conservar antes de continuar.
        </p>
    </header>

    <button x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-5 py-2.5 text-sm font-semibold text-white bg-bearish hover:bg-bearish/80 rounded-xl transition-all duration-200">
        Eliminar Cuenta
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-surface-900">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-white">¿Estás seguro de que quieres eliminar tu cuenta?</h2>

            <p class="mt-2 text-sm text-surface-400">
                Una vez eliminada, todos los recursos y datos serán eliminados permanentemente. Ingresa tu contraseña para confirmar.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">Contraseña</label>
                <input id="password" name="password" type="password" placeholder="Contraseña"
                       class="w-full px-4 py-3 bg-surface-800/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-bearish/50 focus:border-bearish transition-all">
                @error('password', 'userDeletion')
                    <p class="mt-1.5 text-sm text-bearish">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="px-5 py-2.5 text-sm font-medium text-surface-300 hover:text-white bg-surface-800 hover:bg-surface-700 rounded-xl transition-all duration-200">
                    Cancelar
                </button>

                <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-bearish hover:bg-bearish/80 rounded-xl transition-all duration-200">
                    Eliminar Cuenta
                </button>
            </div>
        </form>
    </x-modal>
</section>
