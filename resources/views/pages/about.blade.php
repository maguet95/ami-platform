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
