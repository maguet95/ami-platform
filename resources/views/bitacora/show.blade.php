<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('bitacora.index') }}" class="text-surface-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <h2 class="text-xl font-bold text-white">
                    {{ $trade->tradePair->symbol ?? 'Trade' }}
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $trade->direction === 'long' ? 'bg-bullish/10 text-bullish' : 'bg-bearish/10 text-bearish' }}">
                        {{ strtoupper($trade->direction) }}
                    </span>
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('bitacora.edit', $trade) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 text-sm text-surface-400 hover:text-white border border-surface-700 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                    </svg>
                    Editar
                </a>
                <form method="POST" action="{{ route('bitacora.duplicate', $trade) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-2 text-sm text-surface-400 hover:text-white border border-surface-700 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                        </svg>
                        Duplicar
                    </button>
                </form>
                <div x-data="{ confirmDelete: false }" class="relative">
                    <button @click="confirmDelete = true"
                            class="inline-flex items-center gap-1.5 px-3 py-2 text-sm text-bearish hover:text-white hover:bg-bearish/20 border border-surface-700 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        Eliminar
                    </button>

                    {{-- Delete confirmation modal --}}
                    <div x-show="confirmDelete" x-cloak
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                         @click.self="confirmDelete = false"
                         x-transition>
                        <div class="bg-surface-900 border border-surface-700 rounded-2xl p-6 max-w-sm w-full shadow-xl">
                            <h3 class="text-lg font-semibold text-white">Eliminar trade</h3>
                            <p class="mt-2 text-sm text-surface-400">Estas seguro de que quieres eliminar este trade? Esta accion se puede revertir.</p>
                            <div class="mt-4 flex justify-end gap-3">
                                <button @click="confirmDelete = false"
                                        class="px-4 py-2 text-sm text-surface-400 hover:text-white border border-surface-700 rounded-lg transition">
                                    Cancelar
                                </button>
                                <form method="POST" action="{{ route('bitacora.destroy', $trade) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-4 py-2 text-sm text-white bg-bearish hover:bg-bearish/80 rounded-lg transition">
                                        Si, eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left column: main data --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- General info + execution --}}
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-surface-300">Informacion del trade</h3>
                        <span class="text-sm {{ $trade->getResultColor() }} font-semibold">{{ $trade->getResultLabel() }}</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-surface-500">Par</p>
                            <p class="text-white font-medium">{{ $trade->tradePair->symbol ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-surface-500">Fecha</p>
                            <p class="text-surface-300">{{ $trade->trade_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-surface-500">Temporalidad</p>
                            <p class="text-surface-300">{{ $trade->timeframe ? (\App\Models\ManualTrade::timeframeOptions()[$trade->timeframe] ?? $trade->timeframe) : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-surface-500">Sesion</p>
                            <p class="text-surface-300">{{ $trade->session ? (\App\Models\ManualTrade::sessionOptions()[$trade->session] ?? $trade->session) : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-surface-500">Entrada</p>
                            <p class="text-surface-300 font-mono">${{ number_format($trade->entry_price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-surface-500">Salida</p>
                            <p class="text-surface-300 font-mono">{{ $trade->exit_price ? '$' . number_format($trade->exit_price, 2) : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-surface-500">Stop Loss</p>
                            <p class="text-surface-300 font-mono">{{ $trade->stop_loss ? '$' . number_format($trade->stop_loss, 2) : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-surface-500">Take Profit</p>
                            <p class="text-surface-300 font-mono">{{ $trade->take_profit ? '$' . number_format($trade->take_profit, 2) : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-surface-500">Posicion</p>
                            <p class="text-surface-300 font-mono">{{ $trade->position_size ? number_format($trade->position_size, 4) : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-surface-500">P&L</p>
                            <p class="font-mono font-semibold {{ $trade->getResultColor() }}">
                                {{ $trade->pnl !== null ? ($trade->pnl >= 0 ? '+' : '') . '$' . number_format($trade->pnl, 2) : '—' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-surface-500">P&L %</p>
                            <p class="font-mono {{ $trade->getResultColor() }}">
                                {{ $trade->pnl_percentage !== null ? ($trade->pnl_percentage >= 0 ? '+' : '') . number_format($trade->pnl_percentage, 2) . '%' : '—' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-surface-500">Comision</p>
                            <p class="text-surface-300 font-mono">{{ $trade->commission ? '$' . number_format($trade->commission, 2) : '—' }}</p>
                        </div>
                        @if($trade->risk_reward_planned || $trade->risk_reward_actual)
                        <div>
                            <p class="text-xs text-surface-500">R:R planificado</p>
                            <p class="text-surface-300">{{ $trade->risk_reward_planned ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-surface-500">R:R real</p>
                            <p class="text-surface-300">{{ $trade->risk_reward_actual ?? '—' }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Plan & Discipline --}}
                @if($trade->had_plan || $trade->entry_reason || $trade->invalidation_criteria || $trade->lessons_learned || !empty($trade->mistakes))
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                    <h3 class="text-sm font-semibold text-surface-300 mb-4">Plan y disciplina</h3>

                    <div class="space-y-3 text-sm">
                        @if($trade->had_plan)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-bullish" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="text-surface-300">Tenia plan de trading</span>
                                @if($trade->plan_followed)
                                    <span class="text-xs text-surface-500">— Seguimiento: {{ $trade->plan_followed }}/5</span>
                                @endif
                            </div>
                        @endif

                        @if($trade->entry_reason)
                            <div>
                                <p class="text-xs text-surface-500 mb-1">Razon de entrada</p>
                                <p class="text-surface-300">{{ $trade->entry_reason }}</p>
                            </div>
                        @endif

                        @if($trade->invalidation_criteria)
                            <div>
                                <p class="text-xs text-surface-500 mb-1">Criterio de invalidacion</p>
                                <p class="text-surface-300">{{ $trade->invalidation_criteria }}</p>
                            </div>
                        @endif

                        @if(!empty($trade->mistakes))
                            <div>
                                <p class="text-xs text-surface-500 mb-1">Errores</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($trade->mistakes as $mistake)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-bearish/10 text-bearish">
                                            {{ \App\Models\ManualTrade::mistakeOptions()[$mistake] ?? $mistake }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($trade->lessons_learned)
                            <div>
                                <p class="text-xs text-surface-500 mb-1">Lecciones aprendidas</p>
                                <p class="text-surface-300">{{ $trade->lessons_learned }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Psychology --}}
                @if($trade->emotion_before || $trade->emotion_during || $trade->emotion_after || $trade->confidence_level || $trade->stress_level || $trade->psychology_notes)
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                    <h3 class="text-sm font-semibold text-surface-300 mb-4">Psicologia</h3>

                    <div class="space-y-3">
                        @if($trade->emotion_before || $trade->emotion_during || $trade->emotion_after)
                        <div class="flex flex-wrap gap-2">
                            @foreach(['emotion_before' => 'Antes', 'emotion_during' => 'Durante', 'emotion_after' => 'Despues'] as $field => $label)
                                @if($trade->$field)
                                    <div class="text-center">
                                        <p class="text-[10px] text-surface-500 mb-1">{{ $label }}</p>
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ \App\Models\ManualTrade::emotionColor($trade->$field) }}">
                                            {{ \App\Models\ManualTrade::emotionOptions()[$trade->$field] ?? $trade->$field }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        @if($trade->confidence_level || $trade->stress_level)
                        <div class="flex gap-6 text-sm">
                            @if($trade->confidence_level)
                                <div>
                                    <p class="text-xs text-surface-500">Confianza</p>
                                    <p class="text-surface-300">{{ $trade->confidence_level }}/5</p>
                                </div>
                            @endif
                            @if($trade->stress_level)
                                <div>
                                    <p class="text-xs text-surface-500">Estres</p>
                                    <p class="text-surface-300">{{ $trade->stress_level }}/5</p>
                                </div>
                            @endif
                        </div>
                        @endif

                        @if($trade->psychology_notes)
                            <div>
                                <p class="text-xs text-surface-500 mb-1">Notas</p>
                                <p class="text-sm text-surface-300">{{ $trade->psychology_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Market context --}}
                @if($trade->market_condition || $trade->key_levels || $trade->relevant_news || $trade->additional_confluence)
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                    <h3 class="text-sm font-semibold text-surface-300 mb-4">Contexto de mercado</h3>

                    <div class="space-y-3 text-sm">
                        @if($trade->market_condition)
                            <div>
                                <p class="text-xs text-surface-500">Condicion</p>
                                <p class="text-surface-300">{{ \App\Models\ManualTrade::marketConditionOptions()[$trade->market_condition] ?? $trade->market_condition }}</p>
                            </div>
                        @endif

                        @if($trade->key_levels)
                            <div>
                                <p class="text-xs text-surface-500">Niveles clave</p>
                                <p class="text-surface-300">{{ $trade->key_levels }}</p>
                            </div>
                        @endif

                        @if($trade->relevant_news)
                            <div>
                                <p class="text-xs text-surface-500">Noticias</p>
                                <p class="text-surface-300">{{ $trade->relevant_news }}</p>
                            </div>
                        @endif

                        @if($trade->additional_confluence)
                            <div>
                                <p class="text-xs text-surface-500">Confluencia adicional</p>
                                <p class="text-surface-300">{{ $trade->additional_confluence }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            {{-- Right column: images, reflection, rating --}}
            <div class="space-y-6">

                {{-- Rating & Reflection --}}
                @if($trade->overall_rating || $trade->what_i_did_well || $trade->what_to_improve || $trade->notes)
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                    <h3 class="text-sm font-semibold text-surface-300 mb-4">Reflexion</h3>

                    @if($trade->overall_rating)
                        <div class="flex items-center gap-1 mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $trade->overall_rating ? 'text-ami-400' : 'text-surface-700' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                            <span class="text-xs text-surface-500 ml-1">{{ $trade->overall_rating }}/5</span>
                        </div>
                    @endif

                    @if($trade->would_take_again !== null)
                        <div class="flex items-center gap-2 mb-3 text-sm">
                            @if($trade->would_take_again)
                                <svg class="w-4 h-4 text-bullish" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="text-surface-300">Lo tomaria de nuevo</span>
                            @else
                                <svg class="w-4 h-4 text-bearish" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="text-surface-300">No lo tomaria de nuevo</span>
                            @endif
                        </div>
                    @endif

                    <div class="space-y-3 text-sm">
                        @if($trade->what_i_did_well)
                            <div>
                                <p class="text-xs text-bullish mb-1">Que hice bien</p>
                                <p class="text-surface-300">{{ $trade->what_i_did_well }}</p>
                            </div>
                        @endif

                        @if($trade->what_to_improve)
                            <div>
                                <p class="text-xs text-bearish mb-1">Que mejorar</p>
                                <p class="text-surface-300">{{ $trade->what_to_improve }}</p>
                            </div>
                        @endif

                        @if($trade->notes)
                            <div>
                                <p class="text-xs text-surface-500 mb-1">Notas</p>
                                <p class="text-surface-300">{{ $trade->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Images --}}
                @if($trade->images->isNotEmpty())
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                    <h3 class="text-sm font-semibold text-surface-300 mb-4">Evidencia</h3>

                    <div class="space-y-3">
                        @foreach($trade->images as $image)
                            <div>
                                <a href="{{ Storage::url($image->image_path) }}" target="_blank">
                                    <img src="{{ Storage::url($image->image_path) }}"
                                         alt="{{ $image->caption ?? 'Screenshot' }}"
                                         class="w-full rounded-lg border border-surface-700 hover:border-surface-500 transition">
                                </a>
                                @if($image->caption)
                                    <p class="text-xs text-surface-400 mt-1">{{ $image->caption }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Metadata --}}
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                    <h3 class="text-sm font-semibold text-surface-300 mb-3">Detalles</h3>
                    <div class="space-y-2 text-xs text-surface-500">
                        <p>Creado: {{ $trade->created_at->format('d/m/Y H:i') }}</p>
                        @if($trade->updated_at->ne($trade->created_at))
                            <p>Actualizado: {{ $trade->updated_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
