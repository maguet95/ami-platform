@php
    $isEdit = isset($trade);
    $emotions = \App\Models\ManualTrade::emotionOptions();
    $conditions = \App\Models\ManualTrade::marketConditionOptions();
    $mistakesList = \App\Models\ManualTrade::mistakeOptions();
    $timeframes = \App\Models\ManualTrade::timeframeOptions();
    $sessions = \App\Models\ManualTrade::sessionOptions();
    $currentMistakes = old('mistakes', $isEdit ? ($trade->mistakes ?? []) : []);
@endphp

<div x-data="{ tab: 'general' }" class="space-y-6">

    {{-- Tab navigation --}}
    <div class="flex flex-wrap gap-1 bg-surface-900/80 border border-surface-700/50 rounded-xl p-1.5">
        @foreach([
            'general' => 'General & Ejecucion',
            'plan' => 'Plan & Disciplina',
            'psychology' => 'Psicologia',
            'context' => 'Contexto',
            'evidence' => 'Evidencia',
            'reflection' => 'Reflexion',
        ] as $key => $label)
            <button type="button"
                    @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}'
                        ? 'bg-ami-500/10 text-ami-400 border-ami-500/30'
                        : 'text-surface-400 hover:text-white hover:bg-surface-800/60 border-transparent'"
                    class="flex-1 min-w-[120px] px-3 py-2 text-sm font-medium rounded-lg border transition-all text-center">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="bg-bearish/10 border border-bearish/20 rounded-xl px-4 py-3">
            <p class="text-sm font-medium text-bearish mb-1">Corrige los siguientes errores:</p>
            <ul class="text-xs text-bearish/80 list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TAB 1: General & Execution --}}
    <div x-show="tab === 'general'" x-cloak class="space-y-6">
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5 space-y-5">
            <h3 class="text-sm font-semibold text-surface-300">Informacion general</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Par --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Par <span class="text-bearish">*</span></label>
                    <select name="trade_pair_id" required
                            class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="">Seleccionar...</option>
                        @php $grouped = $pairs->groupBy('market'); @endphp
                        @foreach($grouped as $market => $group)
                            <optgroup label="{{ ucfirst($market) }}">
                                @foreach($group as $pair)
                                    <option value="{{ $pair->id }}" @selected(old('trade_pair_id', $isEdit ? $trade->trade_pair_id : '') == $pair->id)>
                                        {{ $pair->symbol }} — {{ $pair->display_name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                {{-- Direction --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Direccion <span class="text-bearish">*</span></label>
                    <select name="direction" required
                            class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="">Seleccionar...</option>
                        <option value="long" @selected(old('direction', $isEdit ? $trade->direction : '') === 'long')>Long</option>
                        <option value="short" @selected(old('direction', $isEdit ? $trade->direction : '') === 'short')>Short</option>
                    </select>
                </div>

                {{-- Date --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Fecha <span class="text-bearish">*</span></label>
                    <input type="date" name="trade_date" required
                           value="{{ old('trade_date', $isEdit ? $trade->trade_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                </div>

                {{-- Timeframe --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Temporalidad</label>
                    <select name="timeframe"
                            class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="">—</option>
                        @foreach($timeframes as $key => $label)
                            <option value="{{ $key }}" @selected(old('timeframe', $isEdit ? $trade->timeframe : '') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Session --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Sesion</label>
                    <select name="session"
                            class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="">—</option>
                        @foreach($sessions as $key => $label)
                            <option value="{{ $key }}" @selected(old('session', $isEdit ? $trade->session : '') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Estado <span class="text-bearish">*</span></label>
                    <select name="status" required
                            class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="open" @selected(old('status', $isEdit ? $trade->status : 'open') === 'open')>Abierto</option>
                        <option value="closed" @selected(old('status', $isEdit ? $trade->status : '') === 'closed')>Cerrado</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5 space-y-5">
            <h3 class="text-sm font-semibold text-surface-300">Precios y ejecucion</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Entry price --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Precio de entrada <span class="text-bearish">*</span></label>
                    <input type="number" name="entry_price" step="any" required
                           value="{{ old('entry_price', $isEdit ? $trade->entry_price : '') }}"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                           placeholder="0.00">
                </div>

                {{-- Exit price --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Precio de salida</label>
                    <input type="number" name="exit_price" step="any"
                           value="{{ old('exit_price', $isEdit ? $trade->exit_price : '') }}"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                           placeholder="0.00">
                </div>

                {{-- Stop loss --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Stop Loss</label>
                    <input type="number" name="stop_loss" step="any"
                           value="{{ old('stop_loss', $isEdit ? $trade->stop_loss : '') }}"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                           placeholder="0.00">
                </div>

                {{-- Take profit --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Take Profit</label>
                    <input type="number" name="take_profit" step="any"
                           value="{{ old('take_profit', $isEdit ? $trade->take_profit : '') }}"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                           placeholder="0.00">
                </div>

                {{-- Position size --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Tamano de posicion</label>
                    <input type="number" name="position_size" step="any"
                           value="{{ old('position_size', $isEdit ? $trade->position_size : '') }}"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                           placeholder="0.00">
                </div>

                {{-- Commission --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Comision</label>
                    <input type="number" name="commission" step="any"
                           value="{{ old('commission', $isEdit ? $trade->commission : '') }}"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                           placeholder="0.00">
                </div>

                {{-- R:R planned --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">R:R planificado</label>
                    <input type="number" name="risk_reward_planned" step="any"
                           value="{{ old('risk_reward_planned', $isEdit ? $trade->risk_reward_planned : '') }}"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                           placeholder="2.0">
                </div>

                {{-- R:R actual --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">R:R real</label>
                    <input type="number" name="risk_reward_actual" step="any"
                           value="{{ old('risk_reward_actual', $isEdit ? $trade->risk_reward_actual : '') }}"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                           placeholder="1.5">
                </div>

                {{-- PnL --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">P&L ($)</label>
                    <input type="number" name="pnl" step="any"
                           value="{{ old('pnl', $isEdit ? $trade->pnl : '') }}"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                           placeholder="0.00">
                </div>

                {{-- PnL % --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">P&L (%)</label>
                    <input type="number" name="pnl_percentage" step="any"
                           value="{{ old('pnl_percentage', $isEdit ? $trade->pnl_percentage : '') }}"
                           class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                           placeholder="0.00">
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 2: Plan & Discipline --}}
    <div x-show="tab === 'plan'" x-cloak class="space-y-6">
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5 space-y-5">
            <h3 class="text-sm font-semibold text-surface-300">Plan y disciplina</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Had plan --}}
                <div class="flex items-center gap-3">
                    <input type="hidden" name="had_plan" value="0">
                    <input type="checkbox" name="had_plan" value="1" id="had_plan"
                           @checked(old('had_plan', $isEdit ? $trade->had_plan : false))
                           class="w-4 h-4 rounded border-surface-600 bg-surface-800 text-ami-500 focus:ring-ami-500/30">
                    <label for="had_plan" class="text-sm text-surface-300">Tenia un plan antes de entrar</label>
                </div>

                {{-- Plan followed --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Segui el plan (1-5)</label>
                    <select name="plan_followed"
                            class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="">—</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" @selected(old('plan_followed', $isEdit ? $trade->plan_followed : '') == $i)>{{ $i }} — {{ ['Nada','Poco','Parcial','Mucho','Totalmente'][$i-1] }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            {{-- Entry reason --}}
            <div>
                <label class="block text-xs text-surface-500 mb-1">Razon de entrada</label>
                <textarea name="entry_reason" rows="3"
                          class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                          placeholder="Describe por que entraste a este trade...">{{ old('entry_reason', $isEdit ? $trade->entry_reason : '') }}</textarea>
            </div>

            {{-- Invalidation --}}
            <div>
                <label class="block text-xs text-surface-500 mb-1">Criterio de invalidacion</label>
                <textarea name="invalidation_criteria" rows="2"
                          class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                          placeholder="Que invalidaria tu tesis de trading?">{{ old('invalidation_criteria', $isEdit ? $trade->invalidation_criteria : '') }}</textarea>
            </div>

            {{-- Mistakes (multi-select checkboxes) --}}
            <div>
                <label class="block text-xs text-surface-500 mb-2">Errores cometidos</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach($mistakesList as $key => $label)
                        <label class="flex items-center gap-2 px-3 py-2 bg-surface-800/50 border border-surface-700/50 rounded-lg cursor-pointer hover:border-surface-600 transition">
                            <input type="checkbox" name="mistakes[]" value="{{ $key }}"
                                   @checked(in_array($key, $currentMistakes))
                                   class="w-3.5 h-3.5 rounded border-surface-600 bg-surface-800 text-bearish focus:ring-bearish/30">
                            <span class="text-xs text-surface-300">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Lessons learned --}}
            <div>
                <label class="block text-xs text-surface-500 mb-1">Lecciones aprendidas</label>
                <textarea name="lessons_learned" rows="3"
                          class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                          placeholder="Que aprendiste de este trade?">{{ old('lessons_learned', $isEdit ? $trade->lessons_learned : '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- TAB 3: Psychology --}}
    <div x-show="tab === 'psychology'" x-cloak class="space-y-6">
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5 space-y-5">
            <h3 class="text-sm font-semibold text-surface-300">Estado emocional</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach(['emotion_before' => 'Antes del trade', 'emotion_during' => 'Durante el trade', 'emotion_after' => 'Despues del trade'] as $field => $label)
                <div>
                    <label class="block text-xs text-surface-500 mb-1">{{ $label }}</label>
                    <select name="{{ $field }}"
                            class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="">—</option>
                        @foreach($emotions as $key => $emotionLabel)
                            <option value="{{ $key }}" @selected(old($field, $isEdit ? $trade->$field : '') === $key)>{{ $emotionLabel }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Confidence --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Nivel de confianza (1-5)</label>
                    <select name="confidence_level"
                            class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="">—</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" @selected(old('confidence_level', $isEdit ? $trade->confidence_level : '') == $i)>{{ $i }} — {{ ['Muy baja','Baja','Normal','Alta','Muy alta'][$i-1] }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Stress --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Nivel de estres (1-5)</label>
                    <select name="stress_level"
                            class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="">—</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" @selected(old('stress_level', $isEdit ? $trade->stress_level : '') == $i)>{{ $i }} — {{ ['Muy bajo','Bajo','Normal','Alto','Muy alto'][$i-1] }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            {{-- Psychology notes --}}
            <div>
                <label class="block text-xs text-surface-500 mb-1">Notas de psicologia</label>
                <textarea name="psychology_notes" rows="3"
                          class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                          placeholder="Como te sentiste? Que pensamientos tuviste?">{{ old('psychology_notes', $isEdit ? $trade->psychology_notes : '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- TAB 4: Market Context --}}
    <div x-show="tab === 'context'" x-cloak class="space-y-6">
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5 space-y-5">
            <h3 class="text-sm font-semibold text-surface-300">Contexto de mercado</h3>

            <div>
                <label class="block text-xs text-surface-500 mb-1">Condicion del mercado</label>
                <select name="market_condition"
                        class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                    <option value="">—</option>
                    @foreach($conditions as $key => $label)
                        <option value="{{ $key }}" @selected(old('market_condition', $isEdit ? $trade->market_condition : '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs text-surface-500 mb-1">Niveles clave</label>
                <textarea name="key_levels" rows="2"
                          class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                          placeholder="Soportes, resistencias, zonas de interes...">{{ old('key_levels', $isEdit ? $trade->key_levels : '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs text-surface-500 mb-1">Noticias relevantes</label>
                <textarea name="relevant_news" rows="2"
                          class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                          placeholder="Eventos economicos, datos macro, etc.">{{ old('relevant_news', $isEdit ? $trade->relevant_news : '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs text-surface-500 mb-1">Confluencia adicional</label>
                <textarea name="additional_confluence" rows="2"
                          class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                          placeholder="Indicadores, patrones, volumen, etc.">{{ old('additional_confluence', $isEdit ? $trade->additional_confluence : '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- TAB 5: Evidence (images) --}}
    <div x-show="tab === 'evidence'" x-cloak class="space-y-6">
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5 space-y-5">
            <h3 class="text-sm font-semibold text-surface-300">Capturas de pantalla</h3>

            {{-- Existing images (edit mode) --}}
            @if($isEdit && $trade->images->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($trade->images as $image)
                        <div class="relative group">
                            <img src="{{ Storage::url($image->image_path) }}"
                                 alt="{{ $image->caption ?? 'Screenshot' }}"
                                 class="w-full h-32 object-cover rounded-lg border border-surface-700">
                            @if($image->caption)
                                <p class="text-xs text-surface-400 mt-1 truncate">{{ $image->caption }}</p>
                            @endif
                            <form method="POST" action="{{ route('bitacora.image.destroy', $image) }}"
                                  onsubmit="return confirm('Eliminar esta imagen?')"
                                  class="absolute top-1 right-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-6 h-6 flex items-center justify-center bg-bearish/80 hover:bg-bearish text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Upload new images --}}
            <div x-data="{ files: [] }">
                <label class="block text-xs text-surface-500 mb-2">
                    Subir imagenes (max {{ config('journal.manual_max_images', 5) }}, JPG/PNG/WebP, max {{ config('journal.manual_max_image_size', 2048) }}KB cada una)
                </label>
                <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                       @change="files = Array.from($event.target.files)"
                       class="w-full text-sm text-surface-400 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-surface-800 file:text-surface-300 hover:file:bg-surface-700 file:cursor-pointer file:transition">

                {{-- Captions for new images --}}
                <template x-for="(file, index) in files" :key="index">
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-xs text-surface-500 truncate max-w-[150px]" x-text="file.name"></span>
                        <input type="text" :name="'captions[' + index + ']'"
                               class="flex-1 bg-surface-800 border border-surface-700 rounded-lg text-xs text-surface-200 px-2 py-1.5"
                               placeholder="Caption (opcional)">
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- TAB 6: Reflection --}}
    <div x-show="tab === 'reflection'" x-cloak class="space-y-6">
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5 space-y-5">
            <h3 class="text-sm font-semibold text-surface-300">Reflexion y notas</h3>

            <div>
                <label class="block text-xs text-surface-500 mb-1">Que hice bien</label>
                <textarea name="what_i_did_well" rows="3"
                          class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                          placeholder="Aspectos positivos de tu ejecucion...">{{ old('what_i_did_well', $isEdit ? $trade->what_i_did_well : '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs text-surface-500 mb-1">Que puedo mejorar</label>
                <textarea name="what_to_improve" rows="3"
                          class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                          placeholder="Aspectos a mejorar para la proxima...">{{ old('what_to_improve', $isEdit ? $trade->what_to_improve : '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Would take again --}}
                <div class="flex items-center gap-3">
                    <input type="hidden" name="would_take_again" value="">
                    <input type="checkbox" name="would_take_again" value="1" id="would_take_again"
                           @checked(old('would_take_again', $isEdit ? $trade->would_take_again : false))
                           class="w-4 h-4 rounded border-surface-600 bg-surface-800 text-ami-500 focus:ring-ami-500/30">
                    <label for="would_take_again" class="text-sm text-surface-300">Lo tomaria de nuevo</label>
                </div>

                {{-- Overall rating --}}
                <div>
                    <label class="block text-xs text-surface-500 mb-1">Rating general (1-5)</label>
                    <select name="overall_rating"
                            class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2">
                        <option value="">—</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" @selected(old('overall_rating', $isEdit ? $trade->overall_rating : '') == $i)>{{ $i }} — {{ ['Muy malo','Malo','Regular','Bueno','Excelente'][$i-1] }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs text-surface-500 mb-1">Notas adicionales</label>
                <textarea name="notes" rows="4"
                          class="w-full bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-200 px-3 py-2"
                          placeholder="Cualquier otra observacion...">{{ old('notes', $isEdit ? $trade->notes : '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Submit buttons --}}
    <div class="flex items-center justify-between">
        <a href="{{ $isEdit ? route('bitacora.show', $trade) : route('bitacora.index') }}"
           class="px-4 py-2.5 border border-surface-700 text-surface-400 hover:text-white text-sm rounded-lg transition">
            Cancelar
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-ami-500 hover:bg-ami-600 text-white text-sm font-semibold rounded-lg transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
            {{ $isEdit ? 'Actualizar trade' : 'Guardar trade' }}
        </button>
    </div>
</div>
