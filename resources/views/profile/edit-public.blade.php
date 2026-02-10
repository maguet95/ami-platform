<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Perfil Publico</h2>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        @if(session('status') === 'public-profile-updated')
            <div class="bg-bullish/10 border border-bullish/20 rounded-2xl p-4">
                <p class="text-sm text-bullish">Perfil publico actualizado correctamente.</p>
            </div>
        @endif

        {{-- Link del perfil publico --}}
        @if($user->username)
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5"
                 x-data="{ copied: false }">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-lg bg-ami-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-4.5 h-4.5 text-ami-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-surface-500 mb-0.5">Tu perfil publico</p>
                            <p class="text-sm text-ami-400 truncate">{{ route('profile.public', $user) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        @if($user->is_profile_public)
                            <a href="{{ route('profile.public', $user) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border border-surface-700 text-surface-400 hover:text-white hover:bg-surface-800 transition-all duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                                Ver
                            </a>
                        @endif
                        <button type="button"
                                @click="navigator.clipboard.writeText('{{ route('profile.public', $user) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border transition-all duration-200"
                                :class="copied ? 'border-bullish/30 text-bullish bg-bullish/10' : 'border-surface-700 text-surface-400 hover:text-white hover:bg-surface-800'">
                            <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9.75a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                            </svg>
                            <svg x-show="copied" x-cloak class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            <span x-text="copied ? 'Copiado!' : 'Copiar link'"></span>
                        </button>
                    </div>
                </div>
                @if(!$user->is_profile_public)
                    <p class="text-xs text-amber-400/70 mt-2">Tu perfil no es publico aun. Activa la opcion abajo para que otros lo vean.</p>
                @endif
            </div>
        @endif

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8">
            <h3 class="text-lg font-semibold text-white mb-1">Configuracion del Perfil Publico</h3>
            <p class="text-sm text-surface-400 mb-6">Personaliza como te ven otros traders en la comunidad.</p>

            <form method="POST" action="{{ route('profile.update-public') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Toggle Public --}}
                <div class="flex items-center justify-between p-4 bg-surface-800/50 rounded-xl">
                    <div>
                        <p class="text-sm font-medium text-white">Perfil publico</p>
                        <p class="text-xs text-surface-500">Otros traders podran ver tu perfil y estadisticas.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_profile_public" value="0">
                        <input type="checkbox" name="is_profile_public" value="1"
                               class="sr-only peer"
                               {{ $user->is_profile_public ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-surface-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-ami-500"></div>
                    </label>
                </div>

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-sm font-medium text-surface-300 mb-1">Username</label>
                    <div class="flex items-center">
                        <span class="text-sm text-surface-500 mr-2">/trader/</span>
                        <input type="text" name="username" id="username"
                               value="{{ old('username', $user->username) }}"
                               placeholder="tu-username"
                               class="flex-1 bg-surface-800 border border-surface-700 rounded-lg px-3 py-2 text-sm text-white placeholder-surface-600 focus:border-ami-500 focus:ring-1 focus:ring-ami-500">
                    </div>
                    <p class="text-xs text-surface-500 mt-1">Solo letras, numeros, guiones y guiones bajos. 3-30 caracteres.</p>
                    @error('username')
                        <p class="text-xs text-bearish mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Avatar --}}
                <div>
                    <label for="avatar" class="block text-sm font-medium text-surface-300 mb-1">Avatar</label>
                    <div class="flex items-center gap-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                 class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 rounded-full bg-ami-500/20 flex items-center justify-center">
                                <span class="text-2xl font-bold text-ami-400">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <input type="file" name="avatar" id="avatar" accept="image/*"
                               class="text-sm text-surface-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-surface-800 file:text-surface-300 hover:file:bg-surface-700">
                    </div>
                    <p class="text-xs text-surface-500 mt-1">JPG, PNG o GIF. Maximo 2MB.</p>
                    @error('avatar')
                        <p class="text-xs text-bearish mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bio --}}
                <div>
                    <label for="bio" class="block text-sm font-medium text-surface-300 mb-1">Bio</label>
                    <textarea name="bio" id="bio" rows="3"
                              placeholder="Cuentale a la comunidad sobre ti..."
                              class="w-full bg-surface-800 border border-surface-700 rounded-lg px-3 py-2 text-sm text-white placeholder-surface-600 focus:border-ami-500 focus:ring-1 focus:ring-ami-500">{{ old('bio', $user->bio) }}</textarea>
                    <p class="text-xs text-surface-500 mt-1">Maximo 500 caracteres.</p>
                    @error('bio')
                        <p class="text-xs text-bearish mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Location --}}
                <div>
                    <label for="location" class="block text-sm font-medium text-surface-300 mb-1">Ubicacion</label>
                    <input type="text" name="location" id="location"
                           value="{{ old('location', $user->location) }}"
                           placeholder="Bogota, Colombia"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg px-3 py-2 text-sm text-white placeholder-surface-600 focus:border-ami-500 focus:ring-1 focus:ring-ami-500">
                    @error('location')
                        <p class="text-xs text-bearish mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Twitter --}}
                <div>
                    <label for="twitter_handle" class="block text-sm font-medium text-surface-300 mb-1">Twitter / X</label>
                    <div class="flex items-center">
                        <span class="text-sm text-surface-500 mr-2">@</span>
                        <input type="text" name="twitter_handle" id="twitter_handle"
                               value="{{ old('twitter_handle', $user->twitter_handle) }}"
                               placeholder="tu_usuario"
                               class="flex-1 bg-surface-800 border border-surface-700 rounded-lg px-3 py-2 text-sm text-white placeholder-surface-600 focus:border-ami-500 focus:ring-1 focus:ring-ami-500">
                    </div>
                    @error('twitter_handle')
                        <p class="text-xs text-bearish mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Trading Since --}}
                <div>
                    <label for="trading_since" class="block text-sm font-medium text-surface-300 mb-1">Haciendo trading desde</label>
                    <input type="date" name="trading_since" id="trading_since"
                           value="{{ old('trading_since', $user->trading_since?->format('Y-m-d')) }}"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg px-3 py-2 text-sm text-white focus:border-ami-500 focus:ring-1 focus:ring-ami-500">
                    @error('trading_since')
                        <p class="text-xs text-bearish mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-4 border-t border-surface-700/50">
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-200 shadow-lg shadow-ami-500/25">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
