<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Dashboard</h2>
    </x-slot>

    {{-- Welcome Card --}}
    <div class="mb-8 bg-gradient-to-r from-ami-500/10 to-ami-700/10 border border-ami-500/20 rounded-2xl p-6 lg:p-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white">
                    ¡Bienvenido de vuelta, {{ $user->name }}!
                </h3>
                <p class="mt-1 text-sm text-surface-400">Continua tu formacion como trader profesional.</p>
            </div>
            <div class="hidden sm:flex items-center gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-ami-400">Nv. {{ $user->getLevel() }}</p>
                    <p class="text-xs text-surface-500">Nivel</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-amber-400">{{ number_format($user->total_xp) }}</p>
                    <p class="text-xs text-surface-500">XP Total</p>
                </div>
                @if($user->current_streak > 0)
                <div class="text-center">
                    <p class="text-2xl font-bold text-orange-400">{{ $user->current_streak }}d</p>
                    <p class="text-xs text-surface-500">Racha</p>
                </div>
                @endif
            </div>
        </div>
        {{-- XP Progress Bar --}}
        <div class="mt-4">
            <div class="flex items-center justify-between text-xs text-surface-500 mb-1">
                <span>Nivel {{ $user->getLevel() }}</span>
                <span>{{ $user->getLevelProgress() }}/100 XP para nivel {{ $user->getLevel() + 1 }}</span>
            </div>
            <div class="w-full bg-surface-800 rounded-full h-2">
                <div class="bg-gradient-to-r from-ami-500 to-ami-400 h-2 rounded-full transition-all duration-500"
                     style="width: {{ $user->getLevelProgress() }}%"></div>
            </div>
        </div>
    </div>

    {{-- Subscription Banner --}}
    @if($user->hasActiveSubscription())
        <div class="mb-8 bg-bullish/5 border border-bullish/20 rounded-2xl p-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-bullish/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-bullish" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-bullish">Membresia Activa</p>
                    <p class="text-xs text-surface-400">Tienes acceso completo a todos los cursos premium.</p>
                </div>
            </div>
            <a href="{{ route('subscription.portal') }}"
               class="px-4 py-2 text-xs font-medium text-surface-300 bg-surface-800 hover:bg-surface-700 border border-surface-700 rounded-lg transition-all">
                Administrar
            </a>
        </div>
    @else
        <div class="mb-8 bg-ami-500/5 border border-ami-500/20 rounded-2xl p-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-ami-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-ami-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">Desbloquea todo el contenido</p>
                    <p class="text-xs text-surface-400">Accede a cursos premium con una membresia.</p>
                </div>
            </div>
            <a href="{{ route('pricing') }}"
               class="px-4 py-2 text-xs font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-lg transition-all shadow-lg shadow-ami-500/25">
                Ver Planes
            </a>
        </div>
    @endif

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-surface-500 uppercase tracking-wider">Cursos Inscritos</span>
                <div class="w-9 h-9 bg-ami-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-ami-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $enrolledCount }}</p>
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-surface-500 uppercase tracking-wider">Lecciones Completadas</span>
                <div class="w-9 h-9 bg-bullish/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-bullish" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $completedLessons }}</p>
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-surface-500 uppercase tracking-wider">Horas de Estudio</span>
                <div class="w-9 h-9 bg-purple-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $studyHours }}h</p>
        </div>

        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-surface-500 uppercase tracking-wider">Progreso General</span>
                <div class="w-9 h-9 bg-amber-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $avgProgress }}%</p>
        </div>
    </div>

    {{-- Upcoming Live Classes --}}
    @if($upcomingClasses->isNotEmpty())
    <div class="mb-8 bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-white">Proximas Clases en Vivo</h3>
            <a href="{{ route('live-classes.calendar') }}" class="text-xs text-ami-400 hover:text-ami-300">Ver calendario &rarr;</a>
        </div>
        <div class="space-y-3">
            @foreach($upcomingClasses as $liveClass)
                <div class="flex items-center gap-4 p-3 bg-surface-800/50 rounded-xl">
                    <div class="w-10 h-10 bg-ami-500/10 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-ami-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $liveClass->title }}</p>
                        <p class="text-xs text-surface-400">
                            @if($liveClass->starts_at->isToday())
                                Hoy {{ $liveClass->starts_at->format('g:i A') }}
                            @elseif($liveClass->starts_at->isTomorrow())
                                Manana {{ $liveClass->starts_at->format('g:i A') }}
                            @else
                                {{ $liveClass->starts_at->translatedFormat('D j M') }} {{ $liveClass->starts_at->format('g:i A') }}
                            @endif
                            @if($liveClass->instructor)
                                &mdash; {{ $liveClass->instructor->name }}
                            @endif
                        </p>
                    </div>
                    @if($liveClass->isJoinable())
                        <a href="{{ route('live-classes.show', $liveClass) }}"
                           class="px-3 py-1.5 text-xs font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-lg transition-all shadow-lg shadow-ami-500/25">
                            Unirse
                        </a>
                    @else
                        <a href="{{ route('live-classes.show', $liveClass) }}"
                           class="px-3 py-1.5 text-xs font-medium text-surface-400 bg-surface-800 hover:bg-surface-700 border border-surface-700 rounded-lg transition-all">
                            Ver
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Continue Learning --}}
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6">
            <h3 class="text-base font-semibold text-white mb-4">Continuar Aprendiendo</h3>
            @if($currentEnrollment)
                <div class="flex items-center gap-4">
                    @if($currentEnrollment->course->image)
                        <img src="{{ asset('storage/' . $currentEnrollment->course->image) }}"
                             alt="{{ $currentEnrollment->course->title }}"
                             class="w-20 h-14 rounded-lg object-cover shrink-0">
                    @else
                        <div class="w-20 h-14 bg-surface-800 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-surface-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347" />
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ $currentEnrollment->course->title }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="flex-1 bg-surface-800 rounded-full h-1.5">
                                <div class="bg-ami-500 h-1.5 rounded-full" style="width: {{ $currentEnrollment->progress_percent }}%"></div>
                            </div>
                            <span class="text-xs text-surface-500">{{ $currentEnrollment->progress_percent }}%</span>
                        </div>
                        <a href="{{ route('student.course', $currentEnrollment->course) }}"
                           class="inline-block mt-2 text-xs font-semibold text-ami-400 hover:text-ami-300">
                            Continuar &rarr;
                        </a>
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-16 h-16 bg-surface-800 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-surface-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" />
                        </svg>
                    </div>
                    <p class="text-sm text-surface-400 mb-4">Aun no estas inscrito en ningun curso.</p>
                    <a href="{{ route('courses') }}"
                       class="px-5 py-2.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-200 shadow-lg shadow-ami-500/25">
                        Explorar Cursos
                    </a>
                </div>
            @endif
        </div>

        {{-- Recent Achievements --}}
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-white">Logros Recientes</h3>
                @if($user->username && $user->is_profile_public)
                    <a href="{{ route('profile.public', $user) }}" class="text-xs text-ami-400 hover:text-ami-300">Ver todos &rarr;</a>
                @endif
            </div>
            @if($recentAchievements->isNotEmpty())
                <div class="space-y-3">
                    @foreach($recentAchievements as $achievement)
                        <div class="flex items-center gap-3 p-3 bg-surface-800/50 rounded-xl">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                                 style="background: {{ $achievement->getTierColor() }}15; border: 1px solid {{ $achievement->getTierColor() }}30;">
                                <span class="text-lg">
                                    @switch($achievement->tier)
                                        @case('diamond') &#x1f48e; @break
                                        @case('gold') &#x1f3c6; @break
                                        @case('silver') &#x1f948; @break
                                        @default &#x1f949;
                                    @endswitch
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $achievement->name }}</p>
                                <p class="text-xs text-surface-500">+{{ $achievement->xp_reward }} XP</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-16 h-16 bg-surface-800 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-surface-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.003 6.003 0 01-2.573 1.182 6.016 6.016 0 01-5.434 0A6.002 6.002 0 015.73 9.728" />
                        </svg>
                    </div>
                    <p class="text-sm text-surface-400">Completa lecciones para ganar logros.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
