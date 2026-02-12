<x-layouts.app>
    <x-slot:title>Sobre Nosotros</x-slot:title>

    {{-- Hero --}}
    <section class="pt-32 pb-16 relative">
        <div class="absolute inset-0 opacity-[0.02]"
             style="background-image: linear-gradient(rgba(41,98,255,.3) 1px, transparent 1px), linear-gradient(90deg, rgba(41,98,255,.3) 1px, transparent 1px); background-size: 60px 60px;">
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <span class="text-ami-400 text-sm font-semibold uppercase tracking-wider">Sobre AMI</span>
                <h1 class="mt-4 text-4xl sm:text-5xl font-bold text-white leading-tight">
                    Alpha Markets<br><span class="text-ami-400">Institute</span>
                </h1>
                <p class="mt-6 text-lg text-surface-300 leading-relaxed">
                    Somos un instituto dedicado a la formación de traders profesionales. Creemos que el trading es una habilidad
                    que se aprende con metodología, disciplina y las herramientas correctas.
                </p>
            </div>
        </div>
    </section>

    {{-- Mission & Vision --}}
    <section class="py-20 bg-surface-900/50 border-y border-surface-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="p-10 bg-surface-800/40 border border-surface-700/50 rounded-2xl">
                    <div class="w-12 h-12 flex items-center justify-center bg-ami-500/10 text-ami-400 rounded-xl mb-6">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Nuestra Visión</h3>
                    <p class="text-surface-300 leading-relaxed">
                        Ser el instituto de referencia en formación de trading en Latinoamérica, reconocido por la calidad de
                        nuestros egresados y la innovación de nuestras herramientas educativas.
                    </p>
                </div>
                <div class="p-10 bg-surface-800/40 border border-surface-700/50 rounded-2xl">
                    <div class="w-12 h-12 flex items-center justify-center bg-bullish/10 text-bullish rounded-xl mb-6">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Nuestra Misión</h3>
                    <p class="text-surface-300 leading-relaxed">
                        Democratizar el acceso a educación financiera de calidad institucional, brindando a cada estudiante
                        las herramientas, conocimientos y mentalidad necesarios para operar de forma profesional.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Nuestro Equipo --}}
    @if($instructors->isNotEmpty())
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-ami-400 text-sm font-semibold uppercase tracking-wider">Nuestro Equipo</span>
                <h2 class="mt-3 text-3xl font-bold text-white">Conoce a los Instructores</h2>
                <p class="mt-3 text-surface-400 max-w-2xl mx-auto">Traders profesionales con experiencia real en los mercados, comprometidos con tu formación.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($instructors as $instructor)
                    <div class="bg-surface-800/40 border border-surface-700/50 rounded-2xl p-6 hover:border-ami-500/30 transition-all duration-300">
                        <div class="flex flex-col items-center text-center">
                            {{-- Avatar --}}
                            @if($instructor->avatar)
                                <img src="{{ asset('storage/' . $instructor->avatar) }}" alt="{{ $instructor->name }}"
                                     class="w-20 h-20 rounded-full object-cover mb-4">
                            @else
                                <div class="w-20 h-20 rounded-full bg-ami-500/20 flex items-center justify-center mb-4">
                                    <span class="text-2xl font-bold text-ami-400">{{ substr($instructor->name, 0, 1) }}</span>
                                </div>
                            @endif

                            <h3 class="text-lg font-semibold text-white">{{ $instructor->name }}</h3>

                            @if($instructor->headline)
                                <p class="text-sm text-ami-400 mt-1">{{ $instructor->headline }}</p>
                            @endif

                            @if($instructor->bio)
                                <p class="text-sm text-surface-400 mt-3 leading-relaxed">{{ Str::limit($instructor->bio, 120) }}</p>
                            @endif

                            {{-- Social Links --}}
                            <div class="flex items-center gap-3 mt-4">
                                @if($instructor->twitter_handle)
                                    <a href="https://x.com/{{ $instructor->twitter_handle }}" target="_blank" rel="noopener" class="text-surface-500 hover:text-white transition-colors">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    </a>
                                @endif
                                @if($instructor->instagram_handle)
                                    <a href="https://instagram.com/{{ $instructor->instagram_handle }}" target="_blank" rel="noopener" class="text-surface-500 hover:text-white transition-colors">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    </a>
                                @endif
                                @if($instructor->youtube_handle)
                                    <a href="https://youtube.com/@{{ $instructor->youtube_handle }}" target="_blank" rel="noopener" class="text-surface-500 hover:text-white transition-colors">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    </a>
                                @endif
                                @if($instructor->linkedin_url)
                                    <a href="{{ $instructor->linkedin_url }}" target="_blank" rel="noopener" class="text-surface-500 hover:text-white transition-colors">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    </a>
                                @endif
                            </div>

                            {{-- Profile Link --}}
                            @if($instructor->username)
                                <a href="{{ route('profile.public', $instructor->username) }}"
                                   class="inline-flex items-center gap-1.5 mt-4 text-xs text-ami-400 hover:text-ami-300 transition-colors">
                                    Ver perfil completo
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Values --}}
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-white text-center mb-12">Nuestros Valores</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['title' => 'Disciplina', 'desc' => 'La base de todo trader exitoso. Sin disciplina, no hay estrategia que funcione.'],
                    ['title' => 'Transparencia', 'desc' => 'Mostramos resultados reales. Sin capturas editadas ni promesas irreales.'],
                    ['title' => 'Excelencia', 'desc' => 'Cada curso, cada herramienta, cada interacción busca el más alto estándar.'],
                    ['title' => 'Comunidad', 'desc' => 'Crecemos juntos. El trading puede ser solitario, pero no tiene que serlo.'],
                ] as $value)
                <div class="p-6 bg-surface-800/30 border border-surface-700/30 rounded-xl text-center hover:border-ami-500/20 transition-all duration-300">
                    <h3 class="text-lg font-semibold text-white mb-2">{{ $value['title'] }}</h3>
                    <p class="text-sm text-surface-400 leading-relaxed">{{ $value['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
