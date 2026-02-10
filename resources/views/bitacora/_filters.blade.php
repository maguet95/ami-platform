<div x-data="{ open: {{ request()->hasAny(['pair','direction','status','result','from','to','emotion','rating','sort']) ? 'true' : 'false' }} }"
     class="bg-surface-900/80 border border-surface-700/50 rounded-2xl overflow-hidden">
    <button @click="open = !open"
            class="w-full flex items-center justify-between px-5 py-3 text-sm font-medium text-surface-300 hover:text-white transition-colors">
        <span class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
            </svg>
            Filtros
        </span>
        <svg :class="open && 'rotate-180'" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <div x-show="open" x-cloak x-collapse>
        <form method="GET" action="{{ route('bitacora.index') }}" class="px-5 pb-4 flex flex-wrap items-end gap-3">
            {{-- Par --}}
            <div class="flex-1 min-w-[130px]">
                <label class="block text-xs text-surface-500 mb-1">Par</label>
                <select name="pair" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                    <option value="">Todos</option>
                    @foreach($userPairs as $pair)
                        <option value="{{ $pair->id }}" @selected(request('pair') == $pair->id)>{{ $pair->symbol }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Direction --}}
            <div class="min-w-[100px]">
                <label class="block text-xs text-surface-500 mb-1">Direccion</label>
                <select name="direction" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                    <option value="">Todas</option>
                    <option value="long" @selected(request('direction') === 'long')>Long</option>
                    <option value="short" @selected(request('direction') === 'short')>Short</option>
                </select>
            </div>

            {{-- Status --}}
            <div class="min-w-[100px]">
                <label class="block text-xs text-surface-500 mb-1">Estado</label>
                <select name="status" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                    <option value="">Todos</option>
                    <option value="open" @selected(request('status') === 'open')>Abierto</option>
                    <option value="closed" @selected(request('status') === 'closed')>Cerrado</option>
                </select>
            </div>

            {{-- Result --}}
            <div class="min-w-[110px]">
                <label class="block text-xs text-surface-500 mb-1">Resultado</label>
                <select name="result" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                    <option value="">Todos</option>
                    <option value="winning" @selected(request('result') === 'winning')>Ganador</option>
                    <option value="losing" @selected(request('result') === 'losing')>Perdedor</option>
                    <option value="breakeven" @selected(request('result') === 'breakeven')>Breakeven</option>
                </select>
            </div>

            {{-- Emotion --}}
            <div class="min-w-[120px]">
                <label class="block text-xs text-surface-500 mb-1">Emocion (antes)</label>
                <select name="emotion" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                    <option value="">Todas</option>
                    @foreach(\App\Models\ManualTrade::emotionOptions() as $key => $label)
                        <option value="{{ $key }}" @selected(request('emotion') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Rating --}}
            <div class="min-w-[80px]">
                <label class="block text-xs text-surface-500 mb-1">Rating</label>
                <select name="rating" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                    <option value="">Todos</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" @selected(request('rating') == $i)>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            {{-- Date range --}}
            <div class="min-w-[130px]">
                <label class="block text-xs text-surface-500 mb-1">Desde</label>
                <input type="date" name="from" value="{{ request('from') }}" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
            </div>
            <div class="min-w-[130px]">
                <label class="block text-xs text-surface-500 mb-1">Hasta</label>
                <input type="date" name="to" value="{{ request('to') }}" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
            </div>

            {{-- Sort --}}
            <div class="min-w-[120px]">
                <label class="block text-xs text-surface-500 mb-1">Ordenar por</label>
                <select name="sort" class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                    <option value="date" @selected(request('sort', 'date') === 'date')>Fecha</option>
                    <option value="pnl" @selected(request('sort') === 'pnl')>P&L</option>
                    <option value="rating" @selected(request('sort') === 'rating')>Rating</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-ami-500 hover:bg-ami-600 text-white text-sm font-semibold rounded-lg transition">Filtrar</button>
                <a href="{{ route('bitacora.index') }}" class="px-4 py-2 border border-surface-700 text-surface-400 hover:text-white text-sm rounded-lg transition">Limpiar</a>
            </div>
        </form>
    </div>
</div>
