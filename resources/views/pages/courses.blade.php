<x-layouts.app>
    <x-slot:title>Cursos</x-slot:title>

    {{-- Hero --}}
    <section class="pt-32 pb-16 relative">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <span class="text-ami-400 text-sm font-semibold uppercase tracking-wider">Formación</span>
                <h1 class="mt-4 text-4xl sm:text-5xl font-bold text-white leading-tight">
                    Nuestros <span class="text-ami-400">Cursos</span>
                </h1>
                <p class="mt-6 text-lg text-surface-300 leading-relaxed">
                    Programas diseñados para cada nivel. Desde los fundamentos hasta estrategias avanzadas de trading institucional.
                </p>
            </div>
        </div>
    </section>

    {{-- Courses Grid --}}
    <section class="pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($courses->isEmpty())
                <div class="text-center py-16">
                    <p class="text-surface-400">Próximamente se publicarán nuevos cursos.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                    <div class="group bg-surface-800/40 border border-surface-700/50 rounded-2xl overflow-hidden hover:border-ami-500/30 transition-all duration-300 hover:-translate-y-1">
                        {{-- Course Image --}}
                        <div class="h-48 bg-gradient-to-br from-surface-700 to-surface-800 flex items-center justify-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-ami-500/5 to-transparent"></div>
                            @if($course->image)
                                <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('images/logos/isotipo.jpg') }}" alt="AMI" class="h-16 opacity-20 rounded">
                            @endif

                            {{-- Premium/Free Badge --}}
                            <div class="absolute top-3 right-3">
                                @if($course->is_free)
                                    <span class="px-3 py-1 text-xs font-semibold text-bullish bg-bullish/10 backdrop-blur-sm rounded-full border border-bullish/20">
                                        Gratis
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold text-ami-400 bg-ami-500/10 backdrop-blur-sm rounded-full border border-ami-500/20 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                        </svg>
                                        Premium
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-3 py-1 text-xs font-medium rounded-full border
                                    {{ match($course->level) {
                                        'beginner' => 'bg-ami-500/10 text-ami-400 border-ami-500/20',
                                        'intermediate' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                        'advanced' => 'bg-bearish/10 text-bearish border-bearish/20',
                                        default => 'bg-surface-500/10 text-surface-400 border-surface-500/20',
                                    } }}">
                                    {{ $course->getLevelLabel() }}
                                </span>
                                <span class="text-xs text-surface-500">{{ $course->lessons_count }} lecciones</span>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2 group-hover:text-ami-300 transition-colors">{{ $course->title }}</h3>
                            <p class="text-sm text-surface-400 leading-relaxed mb-6">{{ $course->short_description ?? Str::limit(strip_tags($course->description), 120) }}</p>

                            @auth
                                @if(Auth::user()->isEnrolledIn($course))
                                    <a href="{{ route('student.course', $course) }}"
                                       class="inline-flex items-center gap-2 text-sm font-medium text-bullish hover:text-bullish/80 transition-colors">
                                        Continuar aprendiendo
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                        </svg>
                                    </a>
                                @elseif($course->is_free)
                                    <form action="{{ route('student.enroll', $course) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 text-sm font-medium text-ami-400 hover:text-ami-300 transition-colors">
                                            Inscribirme gratis
                                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                            </svg>
                                        </button>
                                    </form>
                                @elseif(Auth::user()->hasActiveSubscription())
                                    <form action="{{ route('student.enroll', $course) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 text-sm font-medium text-ami-400 hover:text-ami-300 transition-colors">
                                            Inscribirme
                                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('pricing') }}"
                                       class="inline-flex items-center gap-2 text-sm font-medium text-ami-400 hover:text-ami-300 transition-colors">
                                        Ver planes
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                        </svg>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('register') }}"
                                   class="inline-flex items-center gap-2 text-sm font-medium text-ami-400 hover:text-ami-300 transition-colors">
                                    Ver detalles
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            {{-- Coming Soon Note --}}
            <div class="mt-12 text-center">
                <p class="text-surface-500 text-sm">Más cursos próximamente. <a href="{{ route('register') }}" class="text-ami-400 hover:text-ami-300">Regístrate</a> para ser el primero en enterarte.</p>
            </div>
        </div>
    </section>
</x-layouts.app>
