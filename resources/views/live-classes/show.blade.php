<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('live-classes.calendar') }}" class="p-1.5 text-surface-400 hover:text-white hover:bg-surface-800 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <h2 class="text-xl font-bold text-white">{{ $liveClass->title }}</h2>
        </div>
    </x-slot>

    @if(session('error'))
        <div class="mb-6 bg-bearish/10 border border-bearish/20 rounded-2xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-bearish shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <p class="text-sm text-bearish">{{ session('error') }}</p>
        </div>
    @endif

    <div class="max-w-3xl">
        {{-- Class Info Card --}}
        <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8 mb-6">
            {{-- Status Badge --}}
            <div class="flex items-center justify-between mb-6">
                @php
                    $statusColor = match($liveClass->status) {
                        'scheduled' => 'blue',
                        'in_progress' => 'green',
                        'completed' => 'gray',
                        'cancelled' => 'red',
                        default => 'gray',
                    };
                @endphp
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-{{ $statusColor }}-500/10 text-{{ $statusColor }}-400 border border-{{ $statusColor }}-500/20">
                    {{ $liveClass->getStatusLabel() }}
                </span>
                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">
                    {{ $liveClass->getPlatformLabel() }}
                </span>
            </div>

            {{-- Details Grid --}}
            <div class="space-y-4">
                {{-- Date & Time --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-surface-800 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">{{ $liveClass->starts_at->translatedFormat('l j \\d\\e F, Y') }}</p>
                        <p class="text-xs text-surface-400">{{ $liveClass->starts_at->format('g:i A') }} &mdash; {{ $liveClass->getEndsAt()->format('g:i A') }} ({{ $liveClass->duration_minutes }} min)</p>
                    </div>
                </div>

                {{-- Instructor --}}
                @if($liveClass->instructor)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-surface-800 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">{{ $liveClass->instructor->name }}</p>
                        <p class="text-xs text-surface-400">Instructor</p>
                    </div>
                </div>
                @endif

                {{-- Course --}}
                @if($liveClass->course)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-surface-800 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">{{ $liveClass->course->title }}</p>
                        <p class="text-xs text-surface-400">Curso</p>
                    </div>
                </div>
                @endif

                {{-- Description --}}
                @if($liveClass->description)
                <div class="pt-4 border-t border-surface-700/50">
                    <p class="text-sm text-surface-300 leading-relaxed">{{ $liveClass->description }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Join Button --}}
        @if($liveClass->status !== 'cancelled')
            @if($liveClass->isJoinable())
                <a href="{{ route('live-classes.join', ['liveClass' => $liveClass, 'token' => $attendance->access_token]) }}"
                   class="flex items-center justify-center gap-3 w-full px-6 py-4 text-base font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-2xl transition-all duration-200 shadow-lg shadow-ami-500/25">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    Unirse a la Clase
                </a>
            @elseif($liveClass->starts_at->isFuture())
                <div class="flex items-center justify-center gap-3 w-full px-6 py-4 text-base font-medium text-surface-400 bg-surface-800/50 border border-surface-700/50 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    La clase aun no ha comenzado. El boton se habilitara 15 minutos antes.
                </div>
            @else
                <div class="flex items-center justify-center gap-3 w-full px-6 py-4 text-base font-medium text-surface-500 bg-surface-800/30 border border-surface-700/30 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Esta clase ya finalizo.
                </div>
            @endif
        @else
            <div class="flex items-center justify-center gap-3 w-full px-6 py-4 text-base font-medium text-bearish bg-bearish/5 border border-bearish/20 rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                Esta clase ha sido cancelada.
            </div>
        @endif
    </div>
</x-app-layout>
