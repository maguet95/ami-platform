<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ $backRoute }}" class="p-1.5 text-surface-400 hover:text-white hover:bg-surface-800 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <h2 class="text-xl font-bold text-white">{{ $title }}</h2>
        </div>
    </x-slot>

    <div class="max-w-7xl space-y-6">

        @php $o = $stats['overview']; @endphp

        @if($o['total_trades'] === 0)
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-8 text-center">
                <svg class="w-12 h-12 text-surface-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-white">Sin datos para mostrar</h3>
                <p class="mt-1 text-sm text-surface-400">Registra operaciones para ver tus estadisticas.</p>
            </div>
        @else

        {{-- Section 1: KPI Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-white">{{ $o['total_trades'] }}</p>
                <p class="text-xs text-surface-500 mt-1">Total Trades</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold {{ $o['win_rate'] >= 50 ? 'text-bullish' : 'text-bearish' }}">{{ $o['win_rate'] }}%</p>
                <p class="text-xs text-surface-500 mt-1">Win Rate</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold {{ $o['total_pnl'] >= 0 ? 'text-bullish' : 'text-bearish' }}">${{ number_format($o['total_pnl'], 2) }}</p>
                <p class="text-xs text-surface-500 mt-1">P&L Total</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-white">{{ number_format($o['profit_factor'], 2) }}</p>
                <p class="text-xs text-surface-500 mt-1">Profit Factor</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-bearish">${{ number_format($o['max_drawdown'], 2) }}</p>
                <p class="text-xs text-surface-500 mt-1">Max Drawdown</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold {{ $o['expectancy'] >= 0 ? 'text-bullish' : 'text-bearish' }}">${{ number_format($o['expectancy'], 2) }}</p>
                <p class="text-xs text-surface-500 mt-1">Expectancy</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-bullish">${{ number_format($o['best_trade'], 2) }}</p>
                <p class="text-xs text-surface-500 mt-1">Mejor Trade</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-bearish">${{ number_format($o['worst_trade'], 2) }}</p>
                <p class="text-xs text-surface-500 mt-1">Peor Trade</p>
            </div>
            @if($o['avg_rr'] !== null)
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-white">{{ number_format($o['avg_rr'], 2) }}</p>
                <p class="text-xs text-surface-500 mt-1">RR Promedio</p>
            </div>
            @endif
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-bullish">{{ $o['profitable_days'] }}/{{ $o['total_days'] }}</p>
                <p class="text-xs text-surface-500 mt-1">Dias Rentables</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold {{ $o['current_streak'] > 0 ? 'text-bullish' : 'text-surface-400' }}">{{ $o['current_streak'] }}</p>
                <p class="text-xs text-surface-500 mt-1">Racha Actual</p>
            </div>
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-amber-400">{{ $o['best_streak'] }}</p>
                <p class="text-xs text-surface-500 mt-1">Mejor Racha</p>
            </div>
        </div>

        {{-- Section 2: Equity Curve --}}
        @if(count($stats['equity_curve']) > 0)
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-surface-300 mb-4">Curva de Equity</h3>
            <div x-data="{
                chart: null,
                init() {
                    const data = @js($stats['equity_curve']);
                    const labels = data.map(d => d.date);
                    const values = data.map(d => d.pnl);

                    this.chart = new Chart(this.$refs.equityCanvas, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'P&L Acumulado',
                                data: values,
                                borderColor: values[values.length-1] >= 0 ? '#22c55e' : '#ef4444',
                                backgroundColor: (ctx) => {
                                    const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, ctx.chart.height);
                                    if (values[values.length-1] >= 0) {
                                        gradient.addColorStop(0, 'rgba(34,197,94,0.15)');
                                        gradient.addColorStop(1, 'rgba(34,197,94,0)');
                                    } else {
                                        gradient.addColorStop(0, 'rgba(239,68,68,0.15)');
                                        gradient.addColorStop(1, 'rgba(239,68,68,0)');
                                    }
                                    return gradient;
                                },
                                fill: true,
                                tension: 0.3,
                                pointRadius: 2,
                                pointHoverRadius: 5,
                                borderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#1e1e2e',
                                    titleColor: '#a1a1aa',
                                    bodyColor: '#fff',
                                    borderColor: '#3f3f46',
                                    borderWidth: 1,
                                    callbacks: {
                                        label: (ctx) => '$' + ctx.parsed.y.toFixed(2)
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    ticks: { color: '#71717a', maxTicksLimit: 10, font: { size: 10 } },
                                    grid: { color: 'rgba(63,63,70,0.3)' }
                                },
                                y: {
                                    ticks: { color: '#71717a', callback: (v) => '$' + v, font: { size: 10 } },
                                    grid: { color: 'rgba(63,63,70,0.3)' }
                                }
                            }
                        }
                    });
                }
            }" class="h-72">
                <canvas x-ref="equityCanvas"></canvas>
            </div>
        </div>
        @endif

        {{-- Section 3: Calendar Heatmap --}}
        @if(count($stats['daily_pnl']) > 0)
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5"
             x-data="{
                currentMonth: new Date().getMonth(),
                currentYear: new Date().getFullYear(),
                dailyData: @js(collect($stats['daily_pnl'])->keyBy('date')->toArray()),
                get monthLabel() {
                    return new Date(this.currentYear, this.currentMonth).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
                },
                get daysInMonth() {
                    return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
                },
                get firstDayOfWeek() {
                    let d = new Date(this.currentYear, this.currentMonth, 1).getDay();
                    return d === 0 ? 6 : d - 1;
                },
                getDayData(day) {
                    const dateStr = this.currentYear + '-' + String(this.currentMonth + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
                    return this.dailyData[dateStr] || null;
                },
                prevMonth() {
                    if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear--; }
                    else { this.currentMonth--; }
                },
                nextMonth() {
                    if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; }
                    else { this.currentMonth++; }
                },
                dayColor(data) {
                    if (!data) return 'bg-surface-800/50 text-surface-600';
                    if (data.pnl > 0) return 'bg-bullish/15 text-bullish border border-bullish/20';
                    if (data.pnl < 0) return 'bg-bearish/15 text-bearish border border-bearish/20';
                    return 'bg-surface-700/30 text-surface-400 border border-surface-700/30';
                }
             }">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-surface-300">Calendario de Trading</h3>
                <div class="flex items-center gap-2">
                    <button @click="prevMonth()" class="p-1.5 text-surface-400 hover:text-white hover:bg-surface-800 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                    </button>
                    <span class="text-sm font-medium text-white capitalize min-w-[130px] text-center" x-text="monthLabel"></span>
                    <button @click="nextMonth()" class="p-1.5 text-surface-400 hover:text-white hover:bg-surface-800 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </button>
                </div>
            </div>

            {{-- Weekday headers --}}
            <div class="grid grid-cols-7 gap-1 mb-1">
                @foreach(['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'] as $day)
                    <div class="text-center text-[10px] text-surface-600 font-medium py-1">{{ $day }}</div>
                @endforeach
            </div>

            {{-- Calendar grid --}}
            <div class="grid grid-cols-7 gap-1">
                <template x-for="i in firstDayOfWeek" :key="'empty-'+i">
                    <div class="aspect-square"></div>
                </template>
                <template x-for="day in daysInMonth" :key="day">
                    <div :class="dayColor(getDayData(day))"
                         class="aspect-square rounded-lg flex flex-col items-center justify-center text-xs cursor-default transition-all"
                         :title="getDayData(day) ? ('$' + getDayData(day).pnl.toFixed(2) + ' | ' + getDayData(day).trades + ' trades') : 'Sin trades'">
                        <span class="font-medium text-[11px]" x-text="day"></span>
                        <template x-if="getDayData(day)">
                            <span class="text-[9px] font-mono" x-text="'$' + getDayData(day).pnl.toFixed(0)"></span>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Legend --}}
            <div class="flex items-center justify-center gap-4 mt-3 text-[10px] text-surface-500">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-bullish/20 border border-bullish/30"></span> Ganancia</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-bearish/20 border border-bearish/30"></span> Perdida</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-surface-700/30 border border-surface-700/50"></span> Breakeven</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-surface-800/50"></span> Sin trades</span>
            </div>
        </div>
        @endif

        {{-- Section 4: Distribution Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Trades by Pair (Donut) --}}
            @if(count($stats['pair_distribution']) > 0)
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-surface-300 mb-4">Trades por Par</h3>
                <div x-data="{
                    chart: null,
                    init() {
                        const data = @js($stats['pair_distribution']);
                        const colors = ['#8b5cf6','#3b82f6','#22c55e','#eab308','#ef4444','#f97316','#06b6d4','#ec4899','#84cc16','#6366f1'];
                        this.chart = new Chart(this.$refs.pairCanvas, {
                            type: 'doughnut',
                            data: {
                                labels: data.map(d => d.symbol),
                                datasets: [{
                                    data: data.map(d => d.count),
                                    backgroundColor: colors.slice(0, data.length),
                                    borderWidth: 0,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%',
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: { color: '#a1a1aa', font: { size: 11 }, padding: 12, boxWidth: 12 }
                                    }
                                }
                            }
                        });
                    }
                }" class="h-64">
                    <canvas x-ref="pairCanvas"></canvas>
                </div>
            </div>
            @endif

            {{-- Long vs Short --}}
            @if(!empty($stats['direction_stats']))
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-surface-300 mb-4">Long vs Short</h3>
                @php $ds = $stats['direction_stats']; @endphp
                <div class="space-y-4">
                    @foreach(['long' => 'Long', 'short' => 'Short'] as $key => $label)
                    @if(isset($ds[$key]))
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm font-medium {{ $key === 'long' ? 'text-bullish' : 'text-bearish' }}">{{ $label }}</span>
                            <span class="text-xs text-surface-400">{{ $ds[$key]['count'] }} trades | {{ $ds[$key]['win_rate'] }}% WR | ${{ number_format($ds[$key]['pnl'], 2) }}</span>
                        </div>
                        <div class="w-full bg-surface-800 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full {{ $key === 'long' ? 'bg-bullish' : 'bg-bearish' }}"
                                 style="width: {{ $ds[$key]['win_rate'] }}%"></div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                {{-- Session stats (manual only) --}}
                @if(count($stats['session_stats']) > 0)
                <h4 class="text-sm font-semibold text-surface-300 mt-6 mb-3">Por Sesion</h4>
                <div x-data="{
                    chart: null,
                    init() {
                        const data = @js($stats['session_stats']);
                        this.chart = new Chart(this.$refs.sessionCanvas, {
                            type: 'bar',
                            data: {
                                labels: data.map(d => d.session),
                                datasets: [{
                                    label: 'P&L',
                                    data: data.map(d => d.pnl),
                                    backgroundColor: data.map(d => d.pnl >= 0 ? 'rgba(34,197,94,0.6)' : 'rgba(239,68,68,0.6)'),
                                    borderRadius: 4,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { ticks: { color: '#71717a', font: { size: 10 } }, grid: { display: false } },
                                    y: { ticks: { color: '#71717a', callback: (v) => '$' + v, font: { size: 10 } }, grid: { color: 'rgba(63,63,70,0.3)' } }
                                }
                            }
                        });
                    }
                }" class="h-40">
                    <canvas x-ref="sessionCanvas"></canvas>
                </div>
                @endif
            </div>
            @endif

            {{-- Timeframe stats (manual only) --}}
            @if(count($stats['timeframe_stats']) > 0)
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-surface-300 mb-4">Por Temporalidad</h3>
                <div x-data="{
                    chart: null,
                    init() {
                        const data = @js($stats['timeframe_stats']);
                        this.chart = new Chart(this.$refs.tfCanvas, {
                            type: 'bar',
                            data: {
                                labels: data.map(d => d.timeframe),
                                datasets: [
                                    {
                                        label: 'Win Rate %',
                                        data: data.map(d => d.win_rate),
                                        backgroundColor: 'rgba(139,92,246,0.6)',
                                        borderRadius: 4,
                                        yAxisID: 'y',
                                    },
                                    {
                                        label: 'Trades',
                                        data: data.map(d => d.count),
                                        backgroundColor: 'rgba(59,130,246,0.4)',
                                        borderRadius: 4,
                                        yAxisID: 'y1',
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { labels: { color: '#a1a1aa', font: { size: 10 }, boxWidth: 10 } }
                                },
                                scales: {
                                    x: { ticks: { color: '#71717a', font: { size: 10 } }, grid: { display: false } },
                                    y: { position: 'left', ticks: { color: '#71717a', callback: (v) => v + '%', font: { size: 10 } }, grid: { color: 'rgba(63,63,70,0.3)' }, max: 100 },
                                    y1: { position: 'right', ticks: { color: '#71717a', font: { size: 10 } }, grid: { display: false } }
                                }
                            }
                        });
                    }
                }" class="h-56">
                    <canvas x-ref="tfCanvas"></canvas>
                </div>
            </div>
            @endif

            {{-- Weekly PnL bar chart --}}
            @if(count($stats['weekly_pnl']) > 0)
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-surface-300 mb-4">PnL Semanal</h3>
                <div x-data="{
                    chart: null,
                    init() {
                        const data = @js($stats['weekly_pnl']);
                        this.chart = new Chart(this.$refs.weeklyCanvas, {
                            type: 'bar',
                            data: {
                                labels: data.map(d => d.week),
                                datasets: [{
                                    label: 'P&L',
                                    data: data.map(d => d.pnl),
                                    backgroundColor: data.map(d => d.pnl >= 0 ? 'rgba(34,197,94,0.6)' : 'rgba(239,68,68,0.6)'),
                                    borderRadius: 4,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { ticks: { color: '#71717a', maxTicksLimit: 8, font: { size: 10 } }, grid: { display: false } },
                                    y: { ticks: { color: '#71717a', callback: (v) => '$' + v, font: { size: 10 } }, grid: { color: 'rgba(63,63,70,0.3)' } }
                                }
                            }
                        });
                    }
                }" class="h-56">
                    <canvas x-ref="weeklyCanvas"></canvas>
                </div>
            </div>
            @endif
        </div>

        {{-- Section 5: Monthly Returns Table --}}
        @if(count($stats['monthly_returns']) > 0)
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl overflow-hidden">
            <div class="p-5 pb-0">
                <h3 class="text-sm font-semibold text-surface-300 mb-4">Retornos Mensuales</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-surface-700/50 text-left text-xs text-surface-500 uppercase tracking-wider">
                            <th class="px-5 py-3">Mes</th>
                            <th class="px-5 py-3 text-center">Trades</th>
                            <th class="px-5 py-3 text-center">Ganados</th>
                            <th class="px-5 py-3 text-center">Perdidos</th>
                            <th class="px-5 py-3 text-center">Win Rate</th>
                            <th class="px-5 py-3 text-right">P&L</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-800/50">
                        @foreach($stats['monthly_returns'] as $month)
                        <tr class="hover:bg-surface-800/30 transition-colors">
                            <td class="px-5 py-3 font-medium text-white capitalize">{{ $month['month_label'] }}</td>
                            <td class="px-5 py-3 text-center text-surface-300">{{ $month['trades'] }}</td>
                            <td class="px-5 py-3 text-center text-bullish">{{ $month['winners'] }}</td>
                            <td class="px-5 py-3 text-center text-bearish">{{ $month['losers'] }}</td>
                            <td class="px-5 py-3 text-center {{ $month['win_rate'] >= 50 ? 'text-bullish' : 'text-bearish' }}">{{ $month['win_rate'] }}%</td>
                            <td class="px-5 py-3 text-right font-semibold font-mono {{ $month['pnl'] >= 0 ? 'text-bullish' : 'text-bearish' }}">
                                {{ $month['pnl'] >= 0 ? '+' : '' }}${{ number_format($month['pnl'], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @endif
    </div>
</x-app-layout>
