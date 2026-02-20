@if($metrics['total_trades'] > 0 || $metrics['open_trades'] > 0)
@php
    $winRateGood = $metrics['win_rate'] >= 50;
    $pnlPositive = $metrics['total_pnl'] >= 0;
    $avgPositive = $metrics['avg_pnl'] >= 0;
@endphp
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">

    <div class="relative bg-surface-900/80 border border-surface-700/50 hover:border-surface-600 rounded-xl pt-7 pb-4 px-4 text-center transition-all duration-200">
        <div class="absolute top-2 right-2"><x-metric-tooltip title="Trades cerrados" body="Total de operaciones cerradas en tu bitácora. No incluye trades abiertos ni cancelados." /></div>
        <p class="text-2xl font-bold text-white tabular-nums">{{ $metrics['total_trades'] }}</p>
        <p class="text-xs text-surface-500 mt-1.5">Trades cerrados</p>
    </div>

    <div class="relative bg-surface-900/80 border border-surface-700/50 hover:border-surface-600 rounded-xl pt-7 pb-4 px-4 text-center transition-all duration-200">
        <div class="absolute top-2 right-2"><x-metric-tooltip title="Win Rate" formula="Trades ganados / Total × 100" body="Porcentaje de trades cerrados con resultado positivo. El tuyo es {{ $metrics['win_rate'] }}% ({{ $metrics['win_count'] }} de {{ $metrics['total_trades'] }}). Un WR alto no garantiza rentabilidad — depende del R:R." /></div>
        <p class="text-2xl font-bold tabular-nums {{ $winRateGood ? 'text-bullish' : 'text-bearish' }}">{{ $metrics['win_rate'] }}%</p>
        <p class="text-xs text-surface-500 mt-1.5">Win Rate</p>
    </div>

    <div class="relative bg-surface-900/80 border border-surface-700/50 hover:border-surface-600 rounded-xl pt-7 pb-4 px-4 text-center transition-all duration-200">
        <div class="absolute top-2 right-2"><x-metric-tooltip title="P&L Total" formula="Σ ganancias − Σ pérdidas" body="Resultado neto acumulado de todos los trades cerrados en tu bitácora. Tu P&L es ${{ number_format($metrics['total_pnl'], 2) }}." /></div>
        <p class="text-2xl font-bold tabular-nums {{ $pnlPositive ? 'text-bullish' : 'text-bearish' }}">${{ number_format($metrics['total_pnl'], 2) }}</p>
        <p class="text-xs text-surface-500 mt-1.5">P&L Total</p>
    </div>

    <div class="relative bg-surface-900/80 border border-surface-700/50 hover:border-surface-600 rounded-xl pt-7 pb-4 px-4 text-center transition-all duration-200">
        <div class="absolute top-2 right-2"><x-metric-tooltip title="Ganados / Perdidos" body="Desglose de tus {{ $metrics['total_trades'] }} trades: {{ $metrics['win_count'] }} cerraron en positivo y {{ $metrics['lose_count'] }} en negativo." /></div>
        <p class="text-2xl font-bold text-white tabular-nums">
            <span class="text-bullish">{{ $metrics['win_count'] }}</span><span class="text-surface-600 mx-0.5 text-xl">/</span><span class="text-bearish">{{ $metrics['lose_count'] }}</span>
        </p>
        <p class="text-xs text-surface-500 mt-1.5">Ganados / Perdidos</p>
    </div>

    <div class="relative bg-surface-900/80 border border-surface-700/50 hover:border-surface-600 rounded-xl pt-7 pb-4 px-4 text-center transition-all duration-200">
        <div class="absolute top-2 right-2"><x-metric-tooltip title="P&L Promedio" formula="P&L total / Total trades" body="Cuánto ganas o pierdes en promedio por operación. El tuyo es ${{ number_format($metrics['avg_pnl'], 2) }}. Si es negativo, revisa tu RR o WR." /></div>
        <p class="text-2xl font-bold tabular-nums {{ $avgPositive ? 'text-bullish' : 'text-bearish' }}">${{ number_format($metrics['avg_pnl'], 2) }}</p>
        <p class="text-xs text-surface-500 mt-1.5">P&L Promedio</p>
    </div>

    <div class="relative bg-surface-900/80 border border-surface-700/50 hover:border-surface-600 rounded-xl pt-7 pb-4 px-4 text-center transition-all duration-200">
        <div class="absolute top-2 right-2"><x-metric-tooltip title="Mayor Ganancia" body="El trade más rentable de tu historial: ${{ number_format($metrics['max_win'], 2) }}. Sirve como referencia del potencial de tus mejores operaciones." /></div>
        <p class="text-2xl font-bold text-bullish tabular-nums">${{ number_format($metrics['max_win'], 2) }}</p>
        <p class="text-xs text-surface-500 mt-1.5">Mayor ganancia</p>
    </div>

    <div class="relative bg-surface-900/80 border border-surface-700/50 hover:border-surface-600 rounded-xl pt-7 pb-4 px-4 text-center transition-all duration-200">
        <div class="absolute top-2 right-2"><x-metric-tooltip title="Mayor Pérdida" body="El trade con mayor pérdida: ${{ number_format($metrics['max_loss'], 2) }}. Compáralo con tu mejor trade para evaluar la consistencia de tu gestión de riesgo." /></div>
        <p class="text-2xl font-bold text-bearish tabular-nums">${{ number_format($metrics['max_loss'], 2) }}</p>
        <p class="text-xs text-surface-500 mt-1.5">Mayor pérdida</p>
    </div>

    @if($metrics['avg_rr_planned'])
    <div class="relative bg-surface-900/80 border border-surface-700/50 hover:border-surface-600 rounded-xl pt-7 pb-4 px-4 text-center transition-all duration-200">
        <div class="absolute top-2 right-2"><x-metric-tooltip title="R:R Planeado Promedio" formula="Ganancia objetivo / Stop loss" body="Ratio Riesgo:Recompensa que planificaste en tus trades. El tuyo es {{ $metrics['avg_rr_planned'] }}. Un RR &gt;2 significa que ganas el doble de lo que arriesgas." /></div>
        <p class="text-2xl font-bold text-white tabular-nums">{{ $metrics['avg_rr_planned'] }}</p>
        <p class="text-xs text-surface-500 mt-1.5">R:R plan. prom.</p>
    </div>
    @endif

    @if($metrics['avg_rating'])
    <div class="relative bg-surface-900/80 border border-surface-700/50 hover:border-surface-600 rounded-xl pt-7 pb-4 px-4 text-center transition-all duration-200">
        <div class="absolute top-2 right-2"><x-metric-tooltip title="Rating de Ejecución" body="Puntuación promedio que te diste en la ejecución de tus trades (1–5). El tuyo es {{ $metrics['avg_rating'] }}/5. Ayuda a separar la calidad de ejecución del resultado final." /></div>
        <p class="text-2xl font-bold text-ami-400 tabular-nums">{{ $metrics['avg_rating'] }}<span class="text-sm text-surface-500 font-normal">/5</span></p>
        <p class="text-xs text-surface-500 mt-1.5">Rating promedio</p>
    </div>
    @endif

    <div class="relative bg-surface-900/80 border border-surface-700/50 hover:border-surface-600 rounded-xl pt-7 pb-4 px-4 text-center transition-all duration-200">
        <div class="absolute top-2 right-2"><x-metric-tooltip title="Racha de Ganadores" body="Trades ganadores consecutivos: racha actual {{ $metrics['current_streak'] }}, mejor racha histórica {{ $metrics['best_streak'] }}. Indica consistencia — pero no operes con exceso de confianza." /></div>
        <p class="text-2xl font-bold text-bullish tabular-nums">{{ $metrics['current_streak'] }}<span class="text-surface-500 text-lg font-normal"> / {{ $metrics['best_streak'] }}</span></p>
        <p class="text-xs text-surface-500 mt-1.5">Racha actual / Mejor</p>
    </div>

</div>
@endif
