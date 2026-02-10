<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Mis Cursos</h2>
    </x-slot>

    @if($enrollments->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-surface-800 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-surface-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Aún no tienes cursos</h3>
            <p class="text-sm text-surface-400 mb-6 max-w-md">Explora nuestro catálogo y comienza tu formación como trader profesional.</p>
            <a href="{{ route('courses') }}"
               class="px-6 py-3 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-200 shadow-lg shadow-ami-500/25">
                Explorar Cursos
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrollments as $enrollment)
                <a href="{{ route('student.course', $enrollment->course) }}"
                   class="group bg-surface-900/80 border border-surface-700/50 rounded-2xl overflow-hidden hover:border-ami-500/30 transition-all duration-300">
                    {{-- Course Image --}}
                    <div class="aspect-video bg-surface-800 relative overflow-hidden">
                        @if($enrollment->course->image)
                            <img src="{{ Storage::url($enrollment->course->image) }}" alt="{{ $enrollment->course->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-surface-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342" />
                                </svg>
                            </div>
                        @endif
                        {{-- Status Badge --}}
                        <div class="absolute top-3 right-3">
                            @if($enrollment->status === 'completed')
                                <span class="px-2.5 py-1 text-xs font-semibold bg-bullish/20 text-bullish rounded-lg">Completado</span>
                            @elseif($enrollment->status === 'active')
                                <span class="px-2.5 py-1 text-xs font-semibold bg-ami-500/20 text-ami-400 rounded-lg">En progreso</span>
                            @endif
                        </div>
                    </div>

                    {{-- Course Info --}}
                    <div class="p-5">
                        <h3 class="text-base font-semibold text-white group-hover:text-ami-400 transition-colors line-clamp-2">
                            {{ $enrollment->course->title }}
                        </h3>
                        <p class="mt-1.5 text-xs text-surface-500">
                            {{ $enrollment->course->getLevelLabel() }} &middot; {{ $enrollment->course->duration_hours }}h
                        </p>

                        {{-- Progress Bar --}}
                        <div class="mt-4">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="text-surface-400">Progreso</span>
                                <span class="font-semibold text-white">{{ $enrollment->progress_percent }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-surface-800 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $enrollment->progress_percent >= 100 ? 'bg-bullish' : 'bg-ami-500' }}"
                                     style="width: {{ $enrollment->progress_percent }}%"></div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-app-layout>
