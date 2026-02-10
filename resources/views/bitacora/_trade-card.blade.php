<a href="{{ route('bitacora.show', $trade) }}"
   class="block bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5 hover:border-surface-600/70 transition-all group">
    <div class="flex items-start justify-between gap-3">
        {{-- Left: pair + direction + date --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <span class="font-semibold text-white group-hover:text-ami-400 transition-colors truncate">
                    {{ $trade->tradePair->symbol ?? 'Sin par' }}
                </span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $trade->direction === 'long' ? 'bg-bullish/10 text-bullish' : 'bg-bearish/10 text-bearish' }}">
                    {{ strtoupper($trade->direction) }}
                </span>
                @if($trade->status === 'open')
                    <span class="inline-flex items-center gap-1 text-xs text-ami-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-ami-400 animate-pulse"></span>
                        Abierto
                    </span>
                @endif
            </div>
            <p class="text-xs text-surface-500">{{ $trade->trade_date->format('d/m/Y') }}
                @if($trade->timeframe) &middot; {{ \App\Models\ManualTrade::timeframeOptions()[$trade->timeframe] ?? $trade->timeframe }} @endif
                @if($trade->session) &middot; {{ \App\Models\ManualTrade::sessionOptions()[$trade->session] ?? $trade->session }} @endif
            </p>
        </div>

        {{-- Right: P&L --}}
        <div class="text-right shrink-0">
            @if($trade->pnl !== null)
                <p class="text-lg font-bold font-mono {{ $trade->getResultColor() }}">
                    {{ $trade->pnl >= 0 ? '+' : '' }}${{ number_format($trade->pnl, 2) }}
                </p>
                @if($trade->pnl_percentage !== null)
                    <p class="text-xs font-mono {{ $trade->getResultColor() }}">
                        {{ $trade->pnl_percentage >= 0 ? '+' : '' }}{{ number_format($trade->pnl_percentage, 2) }}%
                    </p>
                @endif
            @else
                <p class="text-sm text-surface-600">—</p>
            @endif
        </div>
    </div>

    {{-- Bottom row: prices + badges --}}
    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-surface-500">
        <span>Entrada: <span class="text-surface-300 font-mono">${{ number_format($trade->entry_price, 2) }}</span></span>
        @if($trade->exit_price)
            <span>Salida: <span class="text-surface-300 font-mono">${{ number_format($trade->exit_price, 2) }}</span></span>
        @endif
        @if($trade->risk_reward_planned)
            <span>R:R plan: <span class="text-surface-300">{{ $trade->risk_reward_planned }}</span></span>
        @endif
        @if($trade->overall_rating)
            <span class="flex items-center gap-0.5">
                <svg class="w-3 h-3 text-ami-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                {{ $trade->overall_rating }}/5
            </span>
        @endif
    </div>

    {{-- Emotion badges --}}
    @if($trade->emotion_before)
    <div class="mt-2 flex flex-wrap gap-1">
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ \App\Models\ManualTrade::emotionColor($trade->emotion_before) }}">
            {{ \App\Models\ManualTrade::emotionOptions()[$trade->emotion_before] ?? $trade->emotion_before }}
        </span>
    </div>
    @endif

    {{-- Images indicator --}}
    @if($trade->images->isNotEmpty())
    <div class="mt-2 flex items-center gap-1 text-xs text-surface-500">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
        </svg>
        {{ $trade->images->count() }} {{ $trade->images->count() === 1 ? 'imagen' : 'imagenes' }}
    </div>
    @endif
</a>
