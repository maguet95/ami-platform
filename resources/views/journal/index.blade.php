<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
            <h2 class="text-xl font-bold text-white whitespace-nowrap">Trading Journal</h2>
            <div class="flex items-center gap-2">
                @if(config('journal.stats_enabled'))
                <a href="{{ route('journal.stats') }}"
                   class="inline-flex items-center gap-1.5 px-2.5 py-1.5 border border-surface-700 text-surface-300 hover:text-white hover:bg-surface-800 text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                    </svg>
                    Estadisticas
                </a>
                @endif
                @if(config('journal.exports_enabled'))
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 border border-surface-700 text-surface-300 hover:text-white hover:bg-surface-800 text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Exportar
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-40 bg-surface-800 border border-surface-700 rounded-xl shadow-xl z-20 overflow-hidden">
                        <a href="{{ route('journal.export.excel', request()->query()) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-surface-300 hover:text-white hover:bg-surface-700/50 transition">Excel</a>
                        <a href="{{ route('journal.export.pdf', request()->query()) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-surface-300 hover:text-white hover:bg-surface-700/50 transition">PDF</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl space-y-6">

        {{-- All-Time Stats --}}
        @if($allTimeSummary)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-white">{{ $allTimeSummary->total_trades }}</p>
                <p class="text-xs text-surface-500 mt-1">Trades totales</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold {{ $allTimeSummary->win_rate >= 50 ? 'text-bullish' : 'text-bearish' }}">{{ number_format($allTimeSummary->win_rate, 1) }}%</p>
                <p class="text-xs text-surface-500 mt-1">Win rate</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold {{ $allTimeSummary->total_pnl >= 0 ? 'text-bullish' : 'text-bearish' }}">${{ number_format($allTimeSummary->total_pnl, 2) }}</p>
                <p class="text-xs text-surface-500 mt-1">PnL total</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-white">{{ number_format($allTimeSummary->profit_factor, 2) }}</p>
                <p class="text-xs text-surface-500 mt-1">Profit factor</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-bearish">{{ number_format($allTimeSummary->max_drawdown, 1) }}%</p>
                <p class="text-xs text-surface-500 mt-1">Max drawdown</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                @php $avgMinutes = round($allTimeSummary->avg_trade_duration / 60); @endphp
                <p class="text-2xl font-bold text-white">
                    @if($avgMinutes >= 1440)
                        {{ round($avgMinutes / 1440, 1) }}d
                    @elseif($avgMinutes >= 60)
                        {{ round($avgMinutes / 60, 1) }}h
                    @else
                        {{ $avgMinutes }}m
                    @endif
                </p>
                <p class="text-xs text-surface-500 mt-1">Duracion prom.</p>
            </div>
        </div>
        @else
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-8 text-center">
            <svg class="w-12 h-12 text-surface-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-white">Sin datos aun</h3>
            <p class="mt-1 text-sm text-surface-400">Tu journal se llenara automaticamente cuando los workers importen tus operaciones.</p>
        </div>
        @endif

        {{-- Weekly Performance (mini chart) --}}
        @if($weeklySummaries->isNotEmpty())
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-surface-300 mb-4">Rendimiento semanal</h3>
            <div class="flex items-end gap-1 h-24">
                @foreach($weeklySummaries->reverse() as $week)
                    @php
                        $maxPnl = $weeklySummaries->max('total_pnl');
                        $minPnl = $weeklySummaries->min('total_pnl');
                        $range = max(abs($maxPnl), abs($minPnl), 1);
                        $height = min(100, max(10, abs($week->total_pnl) / $range * 100));
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-1" title="{{ $week->period_start->format('d M') }}: ${{ number_format($week->total_pnl, 2) }}">
                        <div class="w-full rounded-sm {{ $week->total_pnl >= 0 ? 'bg-bullish/70' : 'bg-bearish/70' }}"
                             style="height: {{ $height }}%"></div>
                        <span class="text-[9px] text-surface-600">{{ $week->period_start->format('d/m') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Filters --}}
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-4">
            <form method="GET" action="{{ route('journal') }}" class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[120px]">
                    <label class="block text-xs text-surface-500 mb-1">Par</label>
                    <select name="pair" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="">Todos</option>
                        @foreach($userPairs as $pair)
                            <option value="{{ $pair }}" @selected(request('pair') === $pair)>{{ $pair }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[100px]">
                    <label class="block text-xs text-surface-500 mb-1">Direccion</label>
                    <select name="direction" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="">Todas</option>
                        <option value="long" @selected(request('direction') === 'long')>Long</option>
                        <option value="short" @selected(request('direction') === 'short')>Short</option>
                    </select>
                </div>
                <div class="min-w-[100px]">
                    <label class="block text-xs text-surface-500 mb-1">Resultado</label>
                    <select name="result" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="">Todos</option>
                        <option value="winning" @selected(request('result') === 'winning')>Ganador</option>
                        <option value="losing" @selected(request('result') === 'losing')>Perdedor</option>
                    </select>
                </div>
                <div class="min-w-[130px]">
                    <label class="block text-xs text-surface-500 mb-1">Desde</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                </div>
                <div class="min-w-[130px]">
                    <label class="block text-xs text-surface-500 mb-1">Hasta</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-ami-500 hover:bg-ami-600 text-white text-sm font-semibold rounded-lg transition">Filtrar</button>
                    <a href="{{ route('journal') }}" class="px-4 py-2 border border-surface-700 text-surface-400 hover:text-white text-sm rounded-lg transition">Limpiar</a>
                </div>
            </form>
        </div>

        {{-- Trades Table --}}
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl overflow-hidden">
            @if($trades->isEmpty())
                <div class="p-8 text-center text-surface-500 text-sm">
                    No se encontraron operaciones con los filtros seleccionados.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-surface-700/50 text-left text-xs text-surface-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Fecha</th>
                                <th class="px-4 py-3">Par</th>
                                <th class="px-4 py-3">Dir.</th>
                                <th class="px-4 py-3 text-right">Entrada</th>
                                <th class="px-4 py-3 text-right">Salida</th>
                                <th class="px-4 py-3 text-right">Cant.</th>
                                <th class="px-4 py-3 text-right">PnL</th>
                                <th class="px-4 py-3 text-right">%</th>
                                <th class="px-4 py-3">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-800/50">
                            @foreach($trades as $trade)
                            <tr class="hover:bg-surface-800/30 transition-colors">
                                <td class="px-4 py-3 text-surface-300 whitespace-nowrap">{{ $trade->opened_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 font-medium text-white">{{ $trade->tradePair->symbol }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $trade->direction === 'long' ? 'bg-bullish/10 text-bullish' : 'bg-bearish/10 text-bearish' }}">
                                        {{ strtoupper($trade->direction) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-surface-300 font-mono text-xs">${{ number_format($trade->entry_price, 2) }}</td>
                                <td class="px-4 py-3 text-right text-surface-300 font-mono text-xs">{{ $trade->exit_price ? '$' . number_format($trade->exit_price, 2) : '—' }}</td>
                                <td class="px-4 py-3 text-right text-surface-400 font-mono text-xs">{{ number_format($trade->quantity, 4) }}</td>
                                <td class="px-4 py-3 text-right font-semibold font-mono text-xs {{ $trade->pnl >= 0 ? 'text-bullish' : 'text-bearish' }}">
                                    {{ $trade->pnl !== null ? ($trade->pnl >= 0 ? '+' : '') . '$' . number_format($trade->pnl, 2) : '—' }}
                                </td>
                                <td class="px-4 py-3 text-right font-mono text-xs {{ $trade->pnl_percentage >= 0 ? 'text-bullish' : 'text-bearish' }}">
                                    {{ $trade->pnl_percentage !== null ? ($trade->pnl_percentage >= 0 ? '+' : '') . number_format($trade->pnl_percentage, 2) . '%' : '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($trade->status === 'closed')
                                        <span class="text-xs text-surface-500">Cerrado</span>
                                    @elseif($trade->status === 'open')
                                        <span class="inline-flex items-center gap-1 text-xs text-ami-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-ami-400 animate-pulse"></span>
                                            Abierto
                                        </span>
                                    @else
                                        <span class="text-xs text-surface-600">Cancelado</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-4 py-3 border-t border-surface-700/50">
                    {{ $trades->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
