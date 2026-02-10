<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Logros</h2>
    </x-slot>

    <div class="max-w-5xl space-y-6">

        {{-- Stats Header --}}
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6">
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center">
                        <svg class="w-7 h-7 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">{{ $earnedCount }} <span class="text-base font-normal text-surface-400">de {{ $totalCount }}</span></p>
                        <p class="text-sm text-surface-500">logros ganados</p>
                    </div>
                </div>

                <div class="hidden sm:block w-px h-10 bg-surface-700/50"></div>

                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-bullish/10 flex items-center justify-center">
                        <svg class="w-7 h-7 text-bullish" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-bullish">+{{ number_format($totalXpFromAchievements) }}</p>
                        <p class="text-sm text-surface-500">XP por logros</p>
                    </div>
                </div>

                {{-- Progress bar --}}
                <div class="flex-1 hidden md:block">
                    <div class="flex items-center justify-between text-xs text-surface-500 mb-1">
                        <span>Progreso general</span>
                        <span>{{ $totalCount > 0 ? round(($earnedCount / $totalCount) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full h-2.5 bg-surface-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-ami-500 to-amber-400 rounded-full transition-all duration-500"
                             style="width: {{ $totalCount > 0 ? round(($earnedCount / $totalCount) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Achievement Categories --}}
        @php
            $categoryOrder = ['learning' => 'Aprendizaje', 'engagement' => 'Compromiso', 'milestone' => 'Hitos'];
            $categoryIcons = [
                'learning' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />',
                'engagement' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />',
                'milestone' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" />',
            ];
        @endphp

        @foreach($categoryOrder as $key => $label)
            @if(isset($categories[$key]))
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            {!! $categoryIcons[$key] !!}
                        </svg>
                        <h3 class="text-lg font-semibold text-white">{{ $label }}</h3>
                        <span class="text-xs text-surface-500 ml-1">
                            {{ $categories[$key]->where('is_earned', true)->count() }}/{{ $categories[$key]->count() }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($categories[$key] as $achievement)
                            <div class="relative rounded-2xl border p-5 transition-all duration-200
                                        {{ $achievement->is_earned
                                            ? 'bg-surface-900/80 border-surface-700/50'
                                            : 'bg-surface-900/40 border-surface-800/50 opacity-60' }}">

                                {{-- Tier badge --}}
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-2xl">
                                            @switch($achievement->tier)
                                                @case('diamond') &#x1f48e; @break
                                                @case('gold') &#x1f3c6; @break
                                                @case('silver') &#x1f948; @break
                                                @default &#x1f949;
                                            @endswitch
                                        </span>
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                                              style="color: {{ $achievement->getTierColor() }}; background: {{ $achievement->getTierColor() }}15;">
                                            {{ $achievement->getTierLabel() }}
                                        </span>
                                    </div>
                                    <span class="text-xs font-semibold text-amber-400">+{{ $achievement->xp_reward }} XP</span>
                                </div>

                                <h4 class="text-sm font-semibold {{ $achievement->is_earned ? 'text-white' : 'text-surface-400' }}">
                                    {{ $achievement->name }}
                                </h4>
                                <p class="text-xs {{ $achievement->is_earned ? 'text-surface-400' : 'text-surface-600' }} mt-1">
                                    {{ $achievement->description }}
                                </p>

                                @if($achievement->is_earned)
                                    {{-- Earned state --}}
                                    <div class="mt-3 flex items-center gap-1.5 text-xs text-bullish">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Ganado {{ $achievement->earned_at ? \Carbon\Carbon::parse($achievement->earned_at)->format('d M Y') : '' }}</span>
                                    </div>
                                @else
                                    {{-- Progress bar --}}
                                    <div class="mt-3">
                                        <div class="flex items-center justify-between text-xs mb-1">
                                            <span class="text-surface-600">
                                                {{ $achievement->progress['current'] }}/{{ $achievement->progress['target'] }}
                                            </span>
                                            <span class="text-surface-600">{{ $achievement->progress['percent'] }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-surface-800 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500"
                                                 style="width: {{ $achievement->progress['percent'] }}%; background: {{ $achievement->getTierColor() }};">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</x-app-layout>
