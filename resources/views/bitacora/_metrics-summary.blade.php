@if($metrics['total_trades'] > 0 || $metrics['open_trades'] > 0)
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">

    {{-- Trades cerrados --}}
    <div class="relative bg-surface-900 border border-surface-700/60 hover:border-surface-600 rounded-2xl p-5 text-center overflow-hidden transition-all duration-200">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-surface-500/40 to-transparent"></div>
        <p class="text-3xl font-black text-white tabular-nums tracking-tight">{{ $metrics['total_trades'] }}</p>
        <p class="text-[11px] text-surface-500 mt-2 flex items-center justify-center gap-1 font-medium uppercase tracking-wide">
            Trades cerrados
            <x-metric-tooltip
                title="Trades cerrados"
                body="Total de operaciones cerradas registradas en tu bitácora manual. No incluye trades abiertos ni cancelados." />
        </p>
    </div>

    {{-- Win rate --}}
    @php $winRateGood = $metrics['win_rate'] >= 50; @endphp
    <div class="relative bg-surface-900 border {{ $winRateGood ? 'border-bullish/25' : 'border-bearish/25' }} rounded-2xl p-5 text-center overflow-hidden transition-all duration-200">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent {{ $winRateGood ? 'via-bullish/60' : 'via-bearish/60' }} to-transparent"></div>
        <p class="text-3xl font-black tabular-nums tracking-tight {{ $winRateGood ? 'text-bullish' : 'text-bearish' }}">{{ $metrics['win_rate'] }}%</p>
        <p class="text-[11px] text-surface-500 mt-2 flex items-center justify-center gap-1 font-medium uppercase tracking-wide">
            Win rate
            <x-metric-tooltip
                title="Win Rate"
                formula="Trades ganados / Total × 100"
                body="Porcentaje de operaciones cerradas con resultado positivo. El tuyo es {{ $metrics['win_rate'] }}% ({{ $metrics['win_count'] }} ganados de {{ $metrics['total_trades'] }}). Un WR alto no garantiza rentabilidad — depende del R:R." />
        </p>
    </div>

    {{-- P&L total --}}
    @php $pnlPositive = $metrics['total_pnl'] >= 0; @endphp
    <div class="relative bg-surface-900 border {{ $pnlPositive ? 'border-bullish/25' : 'border-bearish/25' }} rounded-2xl p-5 text-center overflow-hidden transition-all duration-200">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent {{ $pnlPositive ? 'via-bullish/60' : 'via-bearish/60' }} to-transparent"></div>
        <p class="text-2xl font-black tabular-nums tracking-tight {{ $pnlPositive ? 'text-bullish' : 'text-bearish' }}">${{ number_format($metrics['total_pnl'], 2) }}</p>
        <p class="text-[11px] text-surface-500 mt-2 flex items-center justify-center gap-1 font-medium uppercase tracking-wide">
            P&L total
            <x-metric-tooltip
                title="P&L Total"
                formula="Σ ganancias − Σ pérdidas"
                body="Resultado neto acumulado en todos los trades cerrados de tu bitácora. Tu P&L actual es ${{ number_format($metrics['total_pnl'], 2) }}." />
        </p>
    </div>

    {{-- Ganados / Perdidos --}}
    <div class="relative bg-surface-900 border border-surface-700/60 hover:border-surface-600 rounded-2xl p-5 text-center overflow-hidden transition-all duration-200">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-surface-500/40 to-transparent"></div>
        <p class="text-3xl font-black text-white tabular-nums tracking-tight">
            <span class="text-bullish">{{ $metrics['win_count'] }}</span><span class="text-surface-600 text-2xl">/</span><span class="text-bearish">{{ $metrics['lose_count'] }}</span>
        </p>
        <p class="text-[11px] text-surface-500 mt-2 flex items-center justify-center gap-1 font-medium uppercase tracking-wide">
            Ganados / Perdidos
            <x-metric-tooltip
                title="Ganados / Perdidos"
                body="Desglose directo de tus {{ $metrics['total_trades'] }} trades: {{ $metrics['win_count'] }} cerraron en positivo y {{ $metrics['lose_count'] }} en negativo." />
        </p>
    </div>

    {{-- P&L promedio --}}
    @php $avgPositive = $metrics['avg_pnl'] >= 0; @endphp
    <div class="relative bg-surface-900 border {{ $avgPositive ? 'border-bullish/20' : 'border-bearish/20' }} rounded-2xl p-5 text-center overflow-hidden transition-all duration-200">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent {{ $avgPositive ? 'via-bullish/40' : 'via-bearish/40' }} to-transparent"></div>
        <p class="text-2xl font-black tabular-nums tracking-tight {{ $avgPositive ? 'text-bullish' : 'text-bearish' }}">${{ number_format($metrics['avg_pnl'], 2) }}</p>
        <p class="text-[11px] text-surface-500 mt-2 flex items-center justify-center gap-1 font-medium uppercase tracking-wide">
            P&L promedio
            <x-metric-tooltip
                title="P&L Promedio por Trade"
                formula="P&L total / Total trades"
                body="Cuánto ganas o pierdes en promedio por operación. El tuyo es ${{ number_format($metrics['avg_pnl'], 2) }}. Si es negativo, necesitas mejorar el RR o el WR." />
        </p>
    </div>

    {{-- Mayor ganancia --}}
    <div class="relative bg-surface-900 border border-bullish/20 hover:border-bullish/40 rounded-2xl p-5 text-center overflow-hidden transition-all duration-200">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-bullish/40 to-transparent"></div>
        <p class="text-2xl font-black text-bullish tabular-nums tracking-tight">${{ number_format($metrics['max_win'], 2) }}</p>
        <p class="text-[11px] text-surface-500 mt-2 flex items-center justify-center gap-1 font-medium uppercase tracking-wide">
            Mayor ganancia
            <x-metric-tooltip
                title="Mayor Ganancia"
                body="El trade más rentable de tu historial en la bitácora: ${{ number_format($metrics['max_win'], 2) }}. Sirve como referencia del potencial de tus mejores operaciones." />
        </p>
    </div>

    {{-- Mayor perdida --}}
    <div class="relative bg-surface-900 border border-bearish/20 hover:border-bearish/40 rounded-2xl p-5 text-center overflow-hidden transition-all duration-200">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-bearish/40 to-transparent"></div>
        <p class="text-2xl font-black text-bearish tabular-nums tracking-tight">${{ number_format($metrics['max_loss'], 2) }}</p>
        <p class="text-[11px] text-surface-500 mt-2 flex items-center justify-center gap-1 font-medium uppercase tracking-wide">
            Mayor perdida
            <x-metric-tooltip
                title="Mayor Pérdida"
                body="El trade con mayor pérdida en tu bitácora: ${{ number_format($metrics['max_loss'], 2) }}. Compáralo con tu mayor ganancia para evaluar si tu gestión de riesgo es consistente." />
        </p>
    </div>

    {{-- R:R plan. prom. --}}
    @if($metrics['avg_rr_planned'])
    <div class="relative bg-surface-900 border border-ami-500/20 hover:border-ami-500/40 rounded-2xl p-5 text-center overflow-hidden transition-all duration-200">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-ami-500/40 to-transparent"></div>
        <p class="text-3xl font-black text-white tabular-nums tracking-tight">{{ $metrics['avg_rr_planned'] }}</p>
        <p class="text-[11px] text-surface-500 mt-2 flex items-center justify-center gap-1 font-medium uppercase tracking-wide">
            R:R plan. prom.
            <x-metric-tooltip
                title="R:R Planeado Promedio"
                formula="Ganancia objetivo / Stop loss"
                body="Ratio Riesgo:Recompensa que planificaste en tus trades antes de entrar. El tuyo es {{ $metrics['avg_rr_planned'] }}. Un RR &gt;2 significa que ganas el doble de lo que arriesgas." />
        </p>
    </div>
    @endif

    {{-- Rating promedio --}}
    @if($metrics['avg_rating'])
    <div class="relative bg-surface-900 border border-ami-500/20 hover:border-ami-500/40 rounded-2xl p-5 text-center overflow-hidden transition-all duration-200">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-ami-500/40 to-transparent"></div>
        <p class="text-3xl font-black text-ami-400 tabular-nums tracking-tight">{{ $metrics['avg_rating'] }}<span class="text-lg text-surface-500 font-medium">/5</span></p>
        <p class="text-[11px] text-surface-500 mt-2 flex items-center justify-center gap-1 font-medium uppercase tracking-wide">
            Rating promedio
            <x-metric-tooltip
                title="Rating de Ejecución"
                body="Puntuación promedio que te diste a ti mismo en la ejecución de tus trades (1–5 estrellas). Tu rating es {{ $metrics['avg_rating'] }}/5. Ayuda a identificar si ejecutas bien tu plan aunque el resultado sea negativo." />
        </p>
    </div>
    @endif

    {{-- Racha actual / Mejor --}}
    <div class="relative bg-surface-900 border border-surface-700/60 hover:border-surface-600 rounded-2xl p-5 text-center overflow-hidden transition-all duration-200">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-surface-500/40 to-transparent"></div>
        <p class="text-3xl font-black text-bullish tabular-nums tracking-tight">
            {{ $metrics['current_streak'] }}<span class="text-lg text-surface-500 font-medium"> / {{ $metrics['best_streak'] }}</span>
        </p>
        <p class="text-[11px] text-surface-500 mt-2 flex items-center justify-center gap-1 font-medium uppercase tracking-wide">
            Racha actual / Mejor
            <x-metric-tooltip
                title="Racha de Trades Ganadores"
                body="Trades ganadores consecutivos: racha actual {{ $metrics['current_streak'] }}, tu mejor racha histórica fue {{ $metrics['best_streak'] }}. Una racha alta indica consistencia, pero no confíes demasiado en ella." />
        </p>
    </div>

</div>
@endif
