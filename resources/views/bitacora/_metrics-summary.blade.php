@if($metrics['total_trades'] > 0 || $metrics['open_trades'] > 0)
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
    <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-white">{{ $metrics['total_trades'] }}</p>
        <p class="text-xs text-surface-500 mt-1">Trades cerrados</p>
    </div>
    <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold {{ $metrics['win_rate'] >= 50 ? 'text-bullish' : 'text-bearish' }}">{{ $metrics['win_rate'] }}%</p>
        <p class="text-xs text-surface-500 mt-1">Win rate</p>
    </div>
    <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold {{ $metrics['total_pnl'] >= 0 ? 'text-bullish' : 'text-bearish' }}">${{ number_format($metrics['total_pnl'], 2) }}</p>
        <p class="text-xs text-surface-500 mt-1">P&L total</p>
    </div>
    <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-white">{{ $metrics['win_count'] }}<span class="text-surface-600">/</span>{{ $metrics['lose_count'] }}</p>
        <p class="text-xs text-surface-500 mt-1">Ganados / Perdidos</p>
    </div>
    <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold {{ $metrics['avg_pnl'] >= 0 ? 'text-bullish' : 'text-bearish' }}">${{ number_format($metrics['avg_pnl'], 2) }}</p>
        <p class="text-xs text-surface-500 mt-1">P&L promedio</p>
    </div>
    <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-bullish">${{ number_format($metrics['max_win'], 2) }}</p>
        <p class="text-xs text-surface-500 mt-1">Mayor ganancia</p>
    </div>
    <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-bearish">${{ number_format($metrics['max_loss'], 2) }}</p>
        <p class="text-xs text-surface-500 mt-1">Mayor perdida</p>
    </div>
    @if($metrics['avg_rr_planned'])
    <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-white">{{ $metrics['avg_rr_planned'] }}</p>
        <p class="text-xs text-surface-500 mt-1">R:R plan. prom.</p>
    </div>
    @endif
    @if($metrics['avg_rating'])
    <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-ami-400">{{ $metrics['avg_rating'] }}<span class="text-sm text-surface-500">/5</span></p>
        <p class="text-xs text-surface-500 mt-1">Rating promedio</p>
    </div>
    @endif
    <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-bullish">{{ $metrics['current_streak'] }} <span class="text-sm text-surface-500">/ {{ $metrics['best_streak'] }}</span></p>
        <p class="text-xs text-surface-500 mt-1">Racha actual / Mejor</p>
    </div>
</div>
@endif
