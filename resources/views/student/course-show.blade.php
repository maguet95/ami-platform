<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('student.courses') }}" class="text-xs text-surface-500 hover:text-ami-400 transition-colors">&larr; Mis Cursos</a>
            <h2 class="text-xl font-bold text-white mt-1">{{ $course->title }}</h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Progress Card --}}
            <div class="bg-gradient-to-r from-ami-500/10 to-ami-700/10 border border-ami-500/20 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-white">Tu Progreso</h3>
                    <span class="text-2xl font-bold text-ami-400">{{ $enrollment->progress_percent }}%</span>
                </div>
                <div class="w-full h-2 bg-surface-800 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 {{ $enrollment->progress_percent >= 100 ? 'bg-bullish' : 'bg-ami-500' }}"
                         style="width: {{ $enrollment->progress_percent }}%"></div>
                </div>
                <p class="mt-2 text-xs text-surface-400">
                    {{ count($completedLessonIds) }} de {{ $course->lessons()->where('is_published', true)->count() }} lecciones completadas
                </p>
            </div>

            {{-- Modules & Lessons --}}
            <div class="space-y-4">
                @foreach($course->modules as $module)
                    @if($module->is_published)
                        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl overflow-hidden">
                            <div class="px-5 py-4 border-b border-surface-700/50">
                                <h3 class="text-sm font-semibold text-white">{{ $module->title }}</h3>
                                @if($module->description)
                                    <p class="mt-1 text-xs text-surface-500">{{ $module->description }}</p>
                                @endif
                            </div>
                            <div class="divide-y divide-surface-700/30">
                                @foreach($module->lessons as $lesson)
                                    <a href="{{ route('student.lesson', [$course, $lesson]) }}"
                                       class="flex items-center gap-4 px-5 py-3.5 hover:bg-surface-800/40 transition-colors group">
                                        {{-- Completion indicator --}}
                                        <div class="shrink-0">
                                            @if(in_array($lesson->id, $completedLessonIds))
                                                <div class="w-7 h-7 rounded-full bg-bullish/20 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-bullish" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-7 h-7 rounded-full border-2 border-surface-600 flex items-center justify-center group-hover:border-ami-500 transition-colors">
                                                    @if($lesson->type === 'video')
                                                        <svg class="w-3.5 h-3.5 text-surface-500 group-hover:text-ami-400 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M8 5v14l11-7z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-3.5 h-3.5 text-surface-500 group-hover:text-ami-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Lesson info --}}
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium {{ in_array($lesson->id, $completedLessonIds) ? 'text-surface-400' : 'text-white group-hover:text-ami-400' }} transition-colors truncate">
                                                {{ $lesson->title }}
                                            </p>
                                        </div>

                                        {{-- Duration --}}
                                        <span class="text-xs text-surface-500 shrink-0">{{ $lesson->getFormattedDuration() }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Course Info Card --}}
            <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Información del Curso</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-surface-400">Nivel</dt>
                        <dd class="text-white font-medium">{{ $course->getLevelLabel() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-surface-400">Duración</dt>
                        <dd class="text-white font-medium">{{ $course->duration_hours }}h</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-surface-400">Módulos</dt>
                        <dd class="text-white font-medium">{{ $course->modules->where('is_published', true)->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-surface-400">Lecciones</dt>
                        <dd class="text-white font-medium">{{ $course->lessons()->where('is_published', true)->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-surface-400">Inscrito</dt>
                        <dd class="text-white font-medium">{{ $enrollment->enrolled_at->format('d/m/Y') }}</dd>
                    </div>
                </dl>
            </div>

            @if($course->instructor)
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-3">Instructor</h3>
                    <div class="flex items-center gap-3">
                        @if($course->instructor->avatar)
                            <img src="{{ asset('storage/' . $course->instructor->avatar) }}" alt="{{ $course->instructor->name }}"
                                 class="w-10 h-10 rounded-full object-cover shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-full bg-ami-500/20 flex items-center justify-center shrink-0">
                                <span class="text-sm font-semibold text-ami-400">{{ substr($course->instructor->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-white">{{ $course->instructor->name }}</p>
                            @if($course->instructor->headline)
                                <p class="text-xs text-surface-500 truncate">{{ $course->instructor->headline }}</p>
                            @endif
                        </div>
                    </div>
                    @if($course->instructor->username)
                        <a href="{{ route('profile.public', $course->instructor->username) }}"
                           class="inline-flex items-center gap-1 mt-3 text-xs text-ami-400 hover:text-ami-300 transition-colors">
                            Ver perfil
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
