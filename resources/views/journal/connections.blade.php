<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
            <h2 class="text-xl font-bold text-white whitespace-nowrap">Conexiones de Broker</h2>
            <a href="{{ route('journal') }}"
               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 border border-surface-700 text-surface-300 hover:text-white hover:bg-surface-800 text-sm font-medium rounded-lg transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
                Volver al Journal
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl space-y-6">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="bg-bullish/10 border border-bullish/30 text-bullish rounded-xl px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
        @endif
        @if(session('warning'))
        <div class="bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 rounded-xl px-4 py-3 text-sm">
            {{ session('warning') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-bearish/10 border border-bearish/30 text-bearish rounded-xl px-4 py-3 text-sm">
            {{ session('error') }}
        </div>
        @endif

        {{-- Active Connections --}}
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-surface-700/50">
                <h3 class="text-sm font-semibold text-surface-300">Conexiones activas</h3>
            </div>
            @if($connections->isEmpty())
                <div class="p-8 text-center text-surface-500 text-sm">
                    No tienes conexiones configuradas. Agrega una abajo.
                </div>
            @else
                <div class="divide-y divide-surface-800/50">
                    @foreach($connections as $conn)
                    <div class="px-5 py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-lg bg-surface-800 flex items-center justify-center flex-shrink-0">
                                @if($conn->type === 'binance')
                                    <span class="text-yellow-400 text-lg font-bold">B</span>
                                @else
                                    <span class="text-ami-400 text-lg font-bold">MT</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-white">{{ $conn->type_label }}</p>
                                <div class="flex items-center gap-2 text-xs text-surface-500">
                                    @if($conn->status === 'active')
                                        <span class="inline-flex items-center gap-1 text-bullish"><span class="w-1.5 h-1.5 rounded-full bg-bullish"></span> Activa</span>
                                    @elseif($conn->status === 'error')
                                        <span class="inline-flex items-center gap-1 text-bearish"><span class="w-1.5 h-1.5 rounded-full bg-bearish"></span> Error</span>
                                    @else
                                        <span class="text-surface-600">Inactiva</span>
                                    @endif
                                    @if($conn->last_synced_at)
                                        <span>Sync: {{ $conn->last_synced_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                                @if($conn->last_error)
                                    <p class="text-xs text-bearish/80 mt-1 truncate">{{ $conn->last_error }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <form method="POST" action="{{ route('journal.connections.toggle-sync', $conn) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1.5 text-xs border rounded-lg transition {{ $conn->sync_enabled ? 'border-bullish/30 text-bullish hover:bg-bullish/10' : 'border-surface-700 text-surface-500 hover:text-white' }}">
                                    {{ $conn->sync_enabled ? 'Sync ON' : 'Sync OFF' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('journal.connections.destroy', $conn) }}" onsubmit="return confirm('Eliminar esta conexion?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-xs border border-bearish/30 text-bearish hover:bg-bearish/10 rounded-lg transition">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- New Connection Form --}}
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl overflow-hidden" x-data="{ type: '' }">
            <div class="px-5 py-4 border-b border-surface-700/50">
                <h3 class="text-sm font-semibold text-surface-300">Nueva conexion</h3>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('journal.connections.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs text-surface-500 mb-1">Tipo de broker</label>
                        <select name="type" x-model="type" required
                                class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="binance">Binance</option>
                            <option value="metatrader4">MetaTrader 4</option>
                            <option value="metatrader5">MetaTrader 5</option>
                        </select>
                        @error('type') <p class="text-xs text-bearish mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Binance fields --}}
                    <template x-if="type === 'binance'">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs text-surface-500 mb-1">API Key (solo lectura)</label>
                                <input type="text" name="api_key" required
                                       class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                                       placeholder="Tu API key de Binance">
                                @error('api_key') <p class="text-xs text-bearish mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs text-surface-500 mb-1">API Secret</label>
                                <input type="password" name="api_secret" required
                                       class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                                       placeholder="Tu API secret de Binance">
                                @error('api_secret') <p class="text-xs text-bearish mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="bg-ami-500/10 border border-ami-500/20 rounded-lg px-3 py-2.5 text-xs text-ami-300">
                                <strong>Importante:</strong> Usa una API key con permisos de <strong>solo lectura</strong>. Nunca habilites trading ni retiros. Tus credenciales se almacenan encriptadas.
                            </div>
                        </div>
                    </template>

                    {{-- MetaTrader fields --}}
                    <template x-if="type === 'metatrader4' || type === 'metatrader5'">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs text-surface-500 mb-1">Login (numero de cuenta)</label>
                                <input type="text" name="login" required
                                       class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                                       placeholder="Ej: 12345678">
                                @error('login') <p class="text-xs text-bearish mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs text-surface-500 mb-1">Investor Password (solo lectura)</label>
                                <input type="password" name="password" required
                                       class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                                       placeholder="Password de inversor">
                                @error('password') <p class="text-xs text-bearish mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs text-surface-500 mb-1">Servidor</label>
                                <input type="text" name="server" required
                                       class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                                       placeholder="Ej: ICMarkets-Demo">
                                @error('server') <p class="text-xs text-bearish mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg px-3 py-2.5 text-xs text-yellow-300">
                                <strong>Nota:</strong> La importacion automatica de MetaTrader estara disponible proximamente. Por ahora, puedes subir tus reportes CSV manualmente abajo.
                            </div>
                        </div>
                    </template>

                    <button type="submit" x-show="type !== ''"
                            class="px-4 py-2 bg-ami-500 hover:bg-ami-600 text-white text-sm font-semibold rounded-lg transition">
                        Guardar conexion
                    </button>
                </form>
            </div>
        </div>

        {{-- CSV Upload --}}
        @if(config('journal.csv_upload_enabled', true))
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-surface-700/50">
                <h3 class="text-sm font-semibold text-surface-300">Importar desde CSV</h3>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('journal.connections.upload-csv') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs text-surface-500 mb-1">Formato</label>
                        <select name="csv_format" required
                                class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                            <option value="">Seleccionar formato...</option>
                            <option value="mt4">MetaTrader 4 (reporte HTML)</option>
                            <option value="mt5">MetaTrader 5 (CSV)</option>
                        </select>
                        @error('csv_format') <p class="text-xs text-bearish mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-surface-500 mb-1">Archivo</label>
                        <input type="file" name="csv_file" required accept=".csv,.htm,.html,.txt"
                               class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2 file:mr-3 file:py-1 file:px-3 file:border-0 file:text-sm file:bg-surface-700 file:text-surface-300 file:rounded-md">
                        @error('csv_file') <p class="text-xs text-bearish mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-surface-600 mt-1">Max {{ config('journal.csv_max_file_size', 5120) / 1024 }}MB. Para MT4, exporta Account History como "Detailed Report".</p>
                    </div>
                    <button type="submit"
                            class="px-4 py-2 bg-ami-500 hover:bg-ami-600 text-white text-sm font-semibold rounded-lg transition">
                        Importar operaciones
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
