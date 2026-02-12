<x-layouts.app>
    <div class="min-h-screen pt-24 pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Preview Banner --}}
            @if($isOwner && !$user->is_profile_public)
                <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-4 mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-sm text-amber-400">Vista previa — Tu perfil no es publico. Solo tu puedes ver esta pagina.</p>
                </div>
            @endif

            {{-- Profile Header --}}
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8 mb-6">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    {{-- Avatar --}}
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                             class="w-24 h-24 rounded-full object-cover shrink-0">
                    @else
                        <div class="w-24 h-24 rounded-full bg-ami-500/20 flex items-center justify-center shrink-0">
                            <span class="text-3xl font-bold text-ami-400">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif

                    <div class="flex-1 text-center sm:text-left">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                            <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                            @if($user->hasRole('instructor'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-ami-500/15 text-ami-400 border border-ami-500/25 self-center sm:self-auto">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
                                    Instructor
                                </span>
                            @endif
                            <button type="button"
                                    x-data="{ copied: false }"
                                    @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border transition-all duration-200 self-center sm:self-auto"
                                    :class="copied ? 'border-bullish/30 text-bullish bg-bullish/10' : 'border-surface-700 text-surface-400 hover:text-white hover:bg-surface-800'">
                                <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                                </svg>
                                <svg x-show="copied" x-cloak class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span x-text="copied ? 'Copiado!' : 'Compartir'"></span>
                            </button>
                        </div>
                        <p class="text-sm text-surface-400">{{'@'}}{{ $user->username }}</p>

                        @if($user->headline)
                            <p class="mt-1 text-sm text-ami-400/80">{{ $user->headline }}</p>
                        @endif

                        @if($user->bio)
                            <p class="mt-2 text-sm text-surface-300">{{ $user->bio }}</p>
                        @endif

                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 mt-3 text-xs text-surface-500">
                            @if($user->location)
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    {{ $user->location }}
                                </span>
                            @endif
                            @if($user->trading_since)
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                    Trading desde {{ $user->trading_since->format('Y') }}
                                </span>
                            @endif
                            @if($user->twitter_handle)
                                <a href="https://x.com/{{ $user->twitter_handle }}" target="_blank" rel="noopener" class="flex items-center gap-1 hover:text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    {{'@'}}{{ $user->twitter_handle }}
                                </a>
                            @endif
                            @if($user->instagram_handle)
                                <a href="https://instagram.com/{{ $user->instagram_handle }}" target="_blank" rel="noopener" class="flex items-center gap-1 hover:text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    {{'@'}}{{ $user->instagram_handle }}
                                </a>
                            @endif
                            @if($user->youtube_handle)
                                <a href="https://youtube.com/@{{ $user->youtube_handle }}" target="_blank" rel="noopener" class="flex items-center gap-1 hover:text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    {{'@'}}{{ $user->youtube_handle }}
                                </a>
                            @endif
                            @if($user->linkedin_url)
                                <a href="{{ $user->linkedin_url }}" target="_blank" rel="noopener" class="flex items-center gap-1 hover:text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    LinkedIn
                                </a>
                            @endif
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Miembro desde {{ $user->created_at->format('M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                    <p class="text-xl font-bold text-ami-400">{{ $user->getLevel() }}</p>
                    <p class="text-xs text-surface-500 mt-1">Nivel</p>
                </div>
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                    <p class="text-xl font-bold text-amber-400">{{ number_format($user->total_xp) }}</p>
                    <p class="text-xs text-surface-500 mt-1">XP Total</p>
                </div>
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                    <p class="text-xl font-bold text-purple-400">#{{ $rank }}</p>
                    <p class="text-xs text-surface-500 mt-1">Ranking</p>
                </div>
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                    <p class="text-xl font-bold text-bullish">{{ $completedCourses }}</p>
                    <p class="text-xs text-surface-500 mt-1">Cursos</p>
                </div>
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                    <p class="text-xl font-bold text-blue-400">{{ $completedLessons }}</p>
                    <p class="text-xs text-surface-500 mt-1">Lecciones</p>
                </div>
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl p-4 text-center">
                    <p class="text-xl font-bold text-orange-400">{{ $user->current_streak }}d</p>
                    <p class="text-xs text-surface-500 mt-1">Racha</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Achievements --}}
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6">
                    <h3 class="text-base font-semibold text-white mb-4">Logros ({{ $achievements->count() }})</h3>
                    @if($achievements->isNotEmpty())
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($achievements as $achievement)
                                <div class="p-3 rounded-xl border"
                                     style="background: {{ $achievement->getTierColor() }}08; border-color: {{ $achievement->getTierColor() }}25;">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-lg">
                                            @switch($achievement->tier)
                                                @case('diamond') &#x1f48e; @break
                                                @case('gold') &#x1f3c6; @break
                                                @case('silver') &#x1f948; @break
                                                @default &#x1f949;
                                            @endswitch
                                        </span>
                                        <span class="text-xs font-medium" style="color: {{ $achievement->getTierColor() }}">{{ $achievement->getTierLabel() }}</span>
                                    </div>
                                    <p class="text-sm font-medium text-white">{{ $achievement->name }}</p>
                                    <p class="text-xs text-surface-500 mt-0.5">{{ $achievement->description }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-surface-500 text-center py-8">Aun no tiene logros.</p>
                    @endif
                </div>

                {{-- Recent Activity --}}
                <div class="space-y-6">
                    <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6">
                        <h3 class="text-base font-semibold text-white mb-4">Actividad Reciente</h3>
                        @if($recentXp->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($recentXp as $tx)
                                    <div class="flex items-center justify-between py-2 border-b border-surface-800 last:border-0">
                                        <div>
                                            <p class="text-sm text-surface-300">{{ $tx->description }}</p>
                                            <p class="text-xs text-surface-600">{{ $tx->created_at->diffForHumans() }}</p>
                                        </div>
                                        <span class="text-sm font-semibold text-bullish">+{{ $tx->amount }} XP</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-surface-500 text-center py-8">Sin actividad reciente.</p>
                        @endif
                    </div>

                    {{-- Trading Journal Stats --}}
                    @if($manualJournalStats || $automaticJournalStats)
                        @foreach([
                            ['stats' => $manualJournalStats, 'curve' => $manualEquityCurve, 'pairs' => $manualPairDistribution, 'label' => 'Bitacora Manual', 'icon' => 'pencil', 'accountType' => null],
                            ['stats' => $automaticJournalStats, 'curve' => $automaticEquityCurve, 'pairs' => $automaticPairDistribution, 'label' => 'Journal Automatico', 'icon' => 'cog', 'accountType' => $user->automatic_journal_account_type],
                        ] as $journal)
                            @if($journal['stats'] && $journal['stats']['total_trades'] > 0)
                            <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6">
                                {{-- Badge --}}
                                <div class="flex items-center gap-2 mb-4">
                                    @if($journal['icon'] === 'pencil')
                                    <div class="w-7 h-7 bg-ami-500/15 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-ami-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </div>
                                    @else
                                    <div class="w-7 h-7 bg-purple-500/15 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    @endif
                                    <div>
                                        <h3 class="text-sm font-semibold text-white">{{ $journal['label'] }}</h3>
                                        @if($journal['accountType'])
                                            <p class="text-[10px] text-surface-500">{{ $journal['accountType'] }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Summary KPIs --}}
                                @php $s = $journal['stats']; @endphp
                                <div class="grid grid-cols-3 gap-2 mb-4">
                                    <div class="text-center p-2 bg-surface-800/50 rounded-lg">
                                        <p class="text-sm font-bold text-white">{{ $s['total_trades'] }}</p>
                                        <p class="text-[10px] text-surface-500">Trades</p>
                                    </div>
                                    <div class="text-center p-2 bg-surface-800/50 rounded-lg">
                                        <p class="text-sm font-bold {{ $s['win_rate'] >= 50 ? 'text-bullish' : 'text-bearish' }}">{{ $s['win_rate'] }}%</p>
                                        <p class="text-[10px] text-surface-500">Win Rate</p>
                                    </div>
                                    <div class="text-center p-2 bg-surface-800/50 rounded-lg">
                                        <p class="text-sm font-bold {{ $s['total_pnl'] >= 0 ? 'text-bullish' : 'text-bearish' }}">${{ number_format($s['total_pnl'], 0) }}</p>
                                        <p class="text-[10px] text-surface-500">P&L</p>
                                    </div>
                                    <div class="text-center p-2 bg-surface-800/50 rounded-lg">
                                        <p class="text-sm font-bold text-white">{{ number_format($s['profit_factor'], 2) }}</p>
                                        <p class="text-[10px] text-surface-500">Profit F.</p>
                                    </div>
                                    <div class="text-center p-2 bg-surface-800/50 rounded-lg">
                                        <p class="text-sm font-bold text-amber-400">{{ $s['best_streak'] }}</p>
                                        <p class="text-[10px] text-surface-500">Mejor Racha</p>
                                    </div>
                                    <div class="text-center p-2 bg-surface-800/50 rounded-lg">
                                        <p class="text-sm font-bold text-white">{{ $s['profitable_days'] }}d</p>
                                        <p class="text-[10px] text-surface-500">Dias Activos</p>
                                    </div>
                                </div>

                                {{-- Mini Equity Curve --}}
                                @if($journal['curve'] && count($journal['curve']) > 1)
                                <div x-data="{
                                    chart: null,
                                    init() {
                                        const data = {{ Js::from($journal['curve']) }};
                                        const values = data.map(d => d.pnl);
                                        this.chart = new Chart(this.$refs.miniEquity, {
                                            type: 'line',
                                            data: {
                                                labels: data.map(d => d.date),
                                                datasets: [{
                                                    data: values,
                                                    borderColor: values[values.length-1] >= 0 ? '#22c55e' : '#ef4444',
                                                    backgroundColor: 'transparent',
                                                    tension: 0.3,
                                                    pointRadius: 0,
                                                    borderWidth: 1.5,
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                                                scales: { x: { display: false }, y: { display: false } }
                                            }
                                        });
                                    }
                                }" class="h-16 mb-3">
                                    <canvas x-ref="miniEquity"></canvas>
                                </div>
                                @endif

                                {{-- Mini Pair Distribution --}}
                                @if($journal['pairs'] && count($journal['pairs']) > 0)
                                <div x-data="{
                                    chart: null,
                                    init() {
                                        const data = {{ Js::from(array_slice($journal['pairs'], 0, 6)) }};
                                        const colors = ['#8b5cf6','#3b82f6','#22c55e','#eab308','#ef4444','#f97316'];
                                        this.chart = new Chart(this.$refs.miniPairs, {
                                            type: 'doughnut',
                                            data: {
                                                labels: data.map(d => d.symbol),
                                                datasets: [{ data: data.map(d => d.count), backgroundColor: colors, borderWidth: 0 }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                cutout: '70%',
                                                plugins: {
                                                    legend: { position: 'right', labels: { color: '#a1a1aa', font: { size: 9 }, padding: 6, boxWidth: 8 } }
                                                }
                                            }
                                        });
                                    }
                                }" class="h-32">
                                    <canvas x-ref="miniPairs"></canvas>
                                </div>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    @elseif(!$manualJournalStats && !$automaticJournalStats)
                        <div class="bg-surface-900/80 border border-surface-700/30 rounded-2xl p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-surface-800 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-surface-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-surface-400">Diario de Trading</p>
                                    <p class="text-xs text-surface-600">Este trader no comparte su journal.</p>
                                </div>
                            </div>
                            @if($isOwner)
                                <a href="{{ route('profile.edit-public') }}" class="inline-flex items-center gap-1.5 mt-3 text-xs text-ami-400 hover:text-ami-300 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    Configura tu journal publico
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Instructor Courses --}}
            @if($instructorCourses->isNotEmpty())
                <div class="mt-6">
                    <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6">
                        <h3 class="text-base font-semibold text-white mb-4">Cursos del Instructor ({{ $instructorCourses->count() }})</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($instructorCourses as $course)
                                <div class="flex items-start gap-4 p-4 rounded-xl border border-surface-700/30 group">
                                    @if($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                                             class="w-16 h-16 rounded-lg object-cover shrink-0">
                                    @else
                                        <div class="w-16 h-16 rounded-lg bg-ami-500/10 flex items-center justify-center shrink-0">
                                            <svg class="w-6 h-6 text-ami-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-white group-hover:text-ami-400 transition-colors truncate">{{ $course->title }}</p>
                                        <p class="text-xs text-surface-500 mt-1">{{ $course->getLevelLabel() }} &middot; {{ $course->lessons_count }} lecciones</p>
                                        @if($course->is_free)
                                            <span class="inline-block mt-1.5 text-[10px] font-semibold text-bullish bg-bullish/10 px-2 py-0.5 rounded-full">Gratis</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
