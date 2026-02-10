<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('student.course', $course) }}" class="text-xs text-surface-500 hover:text-ami-400 transition-colors">&larr; {{ $course->title }}</a>
            <h2 class="text-xl font-bold text-white mt-1">{{ $lesson->title }}</h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Video/Content Area --}}
            @if($lesson->type === 'video' && $lesson->video_url)
                <div class="aspect-video bg-surface-900 rounded-2xl overflow-hidden border border-surface-700/50">
                    <iframe src="{{ $lesson->video_url }}" class="w-full h-full" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            @elseif($lesson->type === 'video')
                <div class="aspect-video bg-surface-900 rounded-2xl border border-surface-700/50 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-surface-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" />
                        </svg>
                        <p class="text-sm text-surface-500">Video no disponible aún</p>
                    </div>
                </div>
            @endif

            {{-- Lesson Content --}}
            @if($lesson->content)
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-6 lg:p-8">
                    <div class="prose prose-invert prose-sm max-w-none
                                prose-headings:text-white prose-p:text-surface-300
                                prose-a:text-ami-400 prose-strong:text-white
                                prose-code:text-ami-300 prose-code:bg-surface-800 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded">
                        {!! $lesson->content !!}
                    </div>
                </div>
            @endif

            {{-- Complete / Navigation --}}
            <div class="flex items-center justify-between">
                <div class="text-sm text-surface-400">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $lesson->getFormattedDuration() }}
                    </span>
                </div>

                @if($progress && $progress->is_completed)
                    <span class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-bullish bg-bullish/10 rounded-xl">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Completada
                    </span>
                @else
                    <form method="POST" action="{{ route('student.lesson.complete', [$course, $lesson]) }}">
                        @csrf
                        <button type="submit"
                                class="px-5 py-2.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-200 shadow-lg shadow-ami-500/25">
                            Marcar como Completada
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Sidebar: Course Outline --}}
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-white px-1">Contenido del Curso</h3>
            @foreach($course->modules as $module)
                @if($module->is_published)
                    <div class="bg-surface-900/80 border border-surface-700/50 rounded-xl overflow-hidden">
                        <div class="px-4 py-3 border-b border-surface-700/30">
                            <p class="text-xs font-semibold text-surface-400 uppercase tracking-wider">{{ $module->title }}</p>
                        </div>
                        <div class="divide-y divide-surface-700/20">
                            @foreach($module->lessons as $moduleLesson)
                                <a href="{{ route('student.lesson', [$course, $moduleLesson]) }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-xs transition-colors
                                          {{ $moduleLesson->id === $lesson->id ? 'bg-ami-500/10 text-ami-400' : 'text-surface-400 hover:text-white hover:bg-surface-800/40' }}">
                                    @if(in_array($moduleLesson->id, $completedLessonIds))
                                        <svg class="w-3.5 h-3.5 text-bullish shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    @elseif($moduleLesson->id === $lesson->id)
                                        <div class="w-3.5 h-3.5 rounded-full bg-ami-500 shrink-0"></div>
                                    @else
                                        <div class="w-3.5 h-3.5 rounded-full border border-surface-600 shrink-0"></div>
                                    @endif
                                    <span class="truncate">{{ $moduleLesson->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</x-app-layout>
