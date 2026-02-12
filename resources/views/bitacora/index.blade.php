<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Bitacora</h2>
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
