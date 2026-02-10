<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-white">Bitacora de Trading</h2>
            <div class="flex items-center gap-2">
                @if(config('journal.stats_enabled'))
                <a href="{{ route('bitacora.stats') }}"
                   class="inline-flex items-center gap-2 px-3 py-2 border border-surface-700 text-surface-300 hover:text-white hover:bg-surface-800 text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                    </svg>
                    Estadisticas
                </a>
                @endif
                @if(config('journal.exports_enabled'))
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="inline-flex items-center gap-2 px-3 py-2 border border-surface-700 text-surface-300 hover:text-white hover:bg-surface-800 text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Exportar
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-40 bg-surface-800 border border-surface-700 rounded-xl shadow-xl z-20 overflow-hidden">
                        <a href="{{ route('bitacora.export.excel', request()->query()) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-surface-300 hover:text-white hover:bg-surface-700/50 transition">
                            <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M10.875 12c-.621 0-1.125.504-1.125 1.125M12 12c.621 0 1.125.504 1.125 1.125m0 0v1.5c0 .621-.504 1.125-1.125 1.125m1.125-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M10.875 15.75c-.621 0-1.125-.504-1.125-1.125v-1.5c0-.621.504-1.125 1.125-1.125" /></svg>
                            Excel
                        </a>
                        <a href="{{ route('bitacora.export.pdf', request()->query()) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-surface-300 hover:text-white hover:bg-surface-700/50 transition">
                            <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                            PDF
                        </a>
                    </div>
                </div>
                @endif
                <a href="{{ route('bitacora.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-ami-500 hover:bg-ami-600 text-white text-sm font-semibold rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nuevo Trade
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl space-y-6">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="bg-bullish/10 border border-bullish/20 rounded-xl px-4 py-3 text-sm text-bullish">
                {{ session('success') }}
            </div>
        @endif

        {{-- Metrics --}}
        @include('bitacora._metrics-summary', ['metrics' => $metrics])

        {{-- Filters --}}
        @include('bitacora._filters', ['userPairs' => $userPairs])

        {{-- Trades list --}}
        @if($trades->isEmpty())
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-8 text-center">
                <svg class="w-12 h-12 text-surface-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-white">Sin trades registrados</h3>
                <p class="mt-1 text-sm text-surface-400">Empieza a documentar tus operaciones para mejorar tu proceso de trading.</p>
                <a href="{{ route('bitacora.create') }}"
                   class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-ami-500 hover:bg-ami-600 text-white text-sm font-semibold rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Registrar mi primer trade
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($trades as $trade)
                    @include('bitacora._trade-card', ['trade' => $trade])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $trades->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
