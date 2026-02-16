<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Calendario de Clases</h2>
    </x-slot>

    <div x-data="calendarApp()" x-init="init()">
        {{-- View Toggle + Month Navigation --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <button @click="prevMonth()" class="p-2 text-surface-400 hover:text-white hover:bg-surface-800 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <h3 class="text-lg font-semibold text-white min-w-[200px] text-center" x-text="monthLabel"></h3>
                <button @click="nextMonth()" class="p-2 text-surface-400 hover:text-white hover:bg-surface-800 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
                <button @click="goToToday()" class="px-3 py-1.5 text-xs font-medium text-ami-400 hover:text-white hover:bg-ami-500/10 border border-ami-500/30 rounded-lg transition-all">
                    Hoy
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button @click="view = 'month'"
                        :class="view === 'month' ? 'bg-ami-500/10 text-ami-400 border-ami-500/30' : 'text-surface-400 hover:text-white border-surface-700'"
                        class="px-3 py-1.5 text-xs font-medium border rounded-lg transition-all">
                    Mes
                </button>
                <button @click="view = 'week'"
                        :class="view === 'week' ? 'bg-ami-500/10 text-ami-400 border-ami-500/30' : 'text-surface-400 hover:text-white border-surface-700'"
                        class="px-3 py-1.5 text-xs font-medium border rounded-lg transition-all">
                    Semana
                </button>
            </div>
        </div>

        {{-- Upcoming Classes (sidebar-style cards) --}}
        @if($upcomingClasses->isNotEmpty())
        <div class="mb-8">
            <h4 class="text-sm font-semibold text-surface-400 uppercase tracking-wider mb-3">Proximas Clases</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($upcomingClasses as $upcoming)
                    <a href="{{ route('live-classes.show', $upcoming) }}"
                       class="bg-surface-900/80 border border-surface-700/50 hover:border-ami-500/30 rounded-2xl p-4 transition-all duration-200 group">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-ami-500/10 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-ami-500/20 transition-all">
                                <svg class="w-5 h-5 text-ami-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate group-hover:text-ami-400 transition-colors">{{ $upcoming->title }}</p>
                                <p class="text-xs text-surface-400 mt-0.5">
                                    {{ $upcoming->starts_at->translatedFormat('D j M') }} &mdash; {{ $upcoming->starts_at->format('g:i A') }}
                                </p>
                                @if($upcoming->instructor)
                                    <p class="text-xs text-surface-500 mt-0.5">{{ $upcoming->instructor->name }}</p>
                                @endif
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                        {{ $upcoming->getPlatformLabel() }}
                                    </span>
                                    @if($upcoming->course)
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-full bg-surface-800 text-surface-400 border border-surface-700">
                                            {{ Str::limit($upcoming->course->title, 20) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Calendar Grid --}}
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl overflow-hidden">
            {{-- Day headers --}}
            <div class="grid grid-cols-7 bg-surface-800/50 border-b border-surface-700/50">
                <template x-for="day in ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom']">
                    <div class="px-2 py-3 text-center text-xs font-semibold text-surface-500 uppercase tracking-wider" x-text="day"></div>
                </template>
            </div>

            {{-- Calendar cells --}}
            <div class="grid grid-cols-7">
                <template x-for="(cell, index) in cells" :key="index">
                    <div :class="{
                        'bg-ami-500/5 border-ami-500/20': cell.isToday,
                        'bg-surface-900/30': !cell.isCurrentMonth,
                        'min-h-[80px] sm:min-h-[100px]': view === 'month',
                        'min-h-[120px]': view === 'week',
                    }"
                    class="border-b border-r border-surface-700/30 p-1.5 sm:p-2">
                        <div class="flex items-center justify-between mb-1">
                            <span :class="{
                                'text-ami-400 font-bold': cell.isToday,
                                'text-white': cell.isCurrentMonth && !cell.isToday,
                                'text-surface-600': !cell.isCurrentMonth,
                            }" class="text-xs sm:text-sm" x-text="cell.day"></span>
                        </div>
                        <div class="space-y-1">
                            <template x-for="cls in cell.classes" :key="cls.id">
                                <a :href="'/clase/' + cls.id"
                                   class="block px-1.5 py-1 text-[10px] sm:text-xs rounded-lg bg-ami-500/10 hover:bg-ami-500/20 text-ami-400 truncate transition-all border border-ami-500/20"
                                   :title="cls.title">
                                    <span class="font-medium" x-text="cls.time"></span>
                                    <span class="hidden sm:inline" x-text="' — ' + cls.title"></span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        function calendarApp() {
            return {
                view: 'month',
                currentDate: new Date(),
                classes: @json($classes->map(fn ($c) => [
                    'id' => $c->id,
                    'title' => $c->title,
                    'starts_at' => $c->starts_at->toISOString(),
                    'platform' => $c->getPlatformLabel(),
                    'instructor' => $c->instructor?->name,
                    'course' => $c->course?->title,
                ])),
                monthLabel: '',
                cells: [],

                init() {
                    this.render();
                    this.$watch('currentDate', () => this.render());
                    this.$watch('view', () => this.render());
                },

                render() {
                    const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    this.monthLabel = months[this.currentDate.getMonth()] + ' ' + this.currentDate.getFullYear();

                    if (this.view === 'month') {
                        this.renderMonth();
                    } else {
                        this.renderWeek();
                    }
                },

                renderMonth() {
                    const year = this.currentDate.getFullYear();
                    const month = this.currentDate.getMonth();
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);

                    // Monday-based week (0=Mon, 6=Sun)
                    let startDow = firstDay.getDay() - 1;
                    if (startDow < 0) startDow = 6;

                    const cells = [];
                    const today = new Date();

                    // Previous month padding
                    for (let i = startDow - 1; i >= 0; i--) {
                        const d = new Date(year, month, -i);
                        cells.push(this.makeCell(d, false, today));
                    }

                    // Current month
                    for (let i = 1; i <= lastDay.getDate(); i++) {
                        const d = new Date(year, month, i);
                        cells.push(this.makeCell(d, true, today));
                    }

                    // Next month padding
                    const remaining = 7 - (cells.length % 7);
                    if (remaining < 7) {
                        for (let i = 1; i <= remaining; i++) {
                            const d = new Date(year, month + 1, i);
                            cells.push(this.makeCell(d, false, today));
                        }
                    }

                    this.cells = cells;
                },

                renderWeek() {
                    const today = new Date();
                    const d = new Date(this.currentDate);
                    let dow = d.getDay() - 1;
                    if (dow < 0) dow = 6;
                    d.setDate(d.getDate() - dow);

                    const cells = [];
                    for (let i = 0; i < 7; i++) {
                        const day = new Date(d);
                        day.setDate(day.getDate() + i);
                        cells.push(this.makeCell(day, true, today));
                    }

                    this.cells = cells;
                },

                makeCell(date, isCurrentMonth, today) {
                    const isToday = date.getDate() === today.getDate() &&
                                    date.getMonth() === today.getMonth() &&
                                    date.getFullYear() === today.getFullYear();

                    const dayClasses = this.classes.filter(c => {
                        const cd = new Date(c.starts_at);
                        return cd.getDate() === date.getDate() &&
                               cd.getMonth() === date.getMonth() &&
                               cd.getFullYear() === date.getFullYear();
                    }).map(c => {
                        const cd = new Date(c.starts_at);
                        return {
                            ...c,
                            time: cd.getHours().toString().padStart(2, '0') + ':' + cd.getMinutes().toString().padStart(2, '0'),
                        };
                    });

                    return { day: date.getDate(), isCurrentMonth, isToday, classes: dayClasses };
                },

                prevMonth() {
                    const d = new Date(this.currentDate);
                    if (this.view === 'month') {
                        d.setMonth(d.getMonth() - 1);
                    } else {
                        d.setDate(d.getDate() - 7);
                    }
                    this.currentDate = d;
                },

                nextMonth() {
                    const d = new Date(this.currentDate);
                    if (this.view === 'month') {
                        d.setMonth(d.getMonth() + 1);
                    } else {
                        d.setDate(d.getDate() + 7);
                    }
                    this.currentDate = d;
                },

                goToToday() {
                    this.currentDate = new Date();
                },
            };
        }
    </script>
</x-app-layout>
