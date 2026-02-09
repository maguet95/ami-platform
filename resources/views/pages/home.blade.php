<x-layouts.app>
    <x-slot:title>Inicio</x-slot:title>

    {{-- Hero Section --}}
    <section class="relative min-h-screen flex items-center overflow-hidden">
        {{-- Animated Background Grid --}}
        <div class="absolute inset-0 opacity-[0.03]"
             style="background-image: linear-gradient(rgba(41,98,255,.3) 1px, transparent 1px), linear-gradient(90deg, rgba(41,98,255,.3) 1px, transparent 1px); background-size: 60px 60px;">
        </div>

        {{-- Gradient Orbs --}}
        <div class="absolute top-1/4 -left-32 w-96 h-96 bg-ami-500/10 rounded-full blur-[128px] animate-pulse"></div>
        <div class="absolute bottom-1/4 -right-32 w-96 h-96 bg-ami-700/10 rounded-full blur-[128px] animate-pulse" style="animation-delay: 2s;"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20">
            <div class="max-w-4xl">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-ami-500/10 border border-ami-500/20 rounded-full mb-8"
                     x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)" x-show="show"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <span class="w-2 h-2 bg-bullish rounded-full animate-pulse"></span>
                    <span class="text-xs font-medium text-ami-300 uppercase tracking-wider">Inscripciones Abiertas</span>
                </div>

                {{-- Heading --}}
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold leading-[1.1] tracking-tight"
                    x-data="{ show: false }" x-init="setTimeout(() => show = true, 400)" x-show="show"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <span class="text-white">Domina los</span><br>
                    <span class="bg-gradient-to-r from-ami-400 via-ami-300 to-ami-500 bg-clip-text text-transparent">Mercados Financieros</span>
                </h1>

                {{-- Subtitle --}}
                <p class="mt-6 text-lg sm:text-xl text-surface-300 max-w-2xl leading-relaxed"
                   x-data="{ show: false }" x-init="setTimeout(() => show = true, 600)" x-show="show"
                   x-transition:enter="transition ease-out duration-700"
                   x-transition:enter-start="opacity-0 translate-y-8"
                   x-transition:enter-end="opacity-100 translate-y-0">
                    Formación institucional en trading. Aprende con metodología profesional, herramientas avanzadas
                    y el respaldo de una comunidad de traders serios.
                </p>

                {{-- CTA Buttons --}}
                <div class="mt-10 flex flex-col sm:flex-row items-start gap-4"
                     x-data="{ show: false }" x-init="setTimeout(() => show = true, 800)" x-show="show"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 translate-y-8"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <a href="{{ route('register') }}"
                       class="group relative inline-flex items-center gap-2 px-8 py-4 text-base font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-300 shadow-xl shadow-ami-500/25 hover:shadow-ami-500/40 hover:-translate-y-0.5">
                        Comenzar Ahora
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    <a href="{{ route('courses') }}"
                       class="inline-flex items-center gap-2 px-8 py-4 text-base font-medium text-surface-300 hover:text-white border border-surface-700 hover:border-surface-500 rounded-xl transition-all duration-300 hover:-translate-y-0.5">
                        Ver Cursos
                    </a>
                </div>

                {{-- Stats --}}
                <div class="mt-16 grid grid-cols-3 gap-8 max-w-lg"
                     x-data="{ show: false }" x-init="setTimeout(() => show = true, 1000)" x-show="show"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 translate-y-8"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold text-white">500+</div>
                        <div class="text-sm text-surface-400 mt-1">Estudiantes</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold text-white">15+</div>
                        <div class="text-sm text-surface-400 mt-1">Cursos</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold text-white">98%</div>
                        <div class="text-sm text-surface-400 mt-1">Satisfacción</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-24 bg-surface-900/50 border-y border-surface-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white">
                    Por qué elegir <span class="text-ami-400">AMI</span>
                </h2>
                <p class="mt-4 text-surface-400 text-lg">
                    No somos otro curso de trading. Somos un instituto con estándares institucionales.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Feature Card 1 --}}
                <div class="group p-8 bg-surface-800/40 hover:bg-surface-800/70 border border-surface-700/50 hover:border-ami-500/30 rounded-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 flex items-center justify-center bg-ami-500/10 text-ami-400 rounded-xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3">Metodología Institucional</h3>
                    <p class="text-surface-400 text-sm leading-relaxed">Enseñamos cómo operan las instituciones financieras. No indicadores mágicos, sino estructura de mercado real.</p>
                </div>

                {{-- Feature Card 2 --}}
                <div class="group p-8 bg-surface-800/40 hover:bg-surface-800/70 border border-surface-700/50 hover:border-ami-500/30 rounded-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 flex items-center justify-center bg-bullish/10 text-bullish rounded-xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3">Trading Journal Premium</h3>
                    <p class="text-surface-400 text-sm leading-relaxed">Registra, analiza y mejora tu operativa con nuestro journal automatizado. Estadísticas en tiempo real de tu rendimiento.</p>
                </div>

                {{-- Feature Card 3 --}}
                <div class="group p-8 bg-surface-800/40 hover:bg-surface-800/70 border border-surface-700/50 hover:border-ami-500/30 rounded-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 flex items-center justify-center bg-ami-500/10 text-ami-400 rounded-xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3">Comunidad de Traders</h3>
                    <p class="text-surface-400 text-sm leading-relaxed">Conecta con una comunidad seria de traders. Comparte análisis, ideas y crece junto a personas con tus mismos objetivos.</p>
                </div>

                {{-- Feature Card 4 --}}
                <div class="group p-8 bg-surface-800/40 hover:bg-surface-800/70 border border-surface-700/50 hover:border-ami-500/30 rounded-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 flex items-center justify-center bg-ami-500/10 text-ami-400 rounded-xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3">Contenido en Video HD</h3>
                    <p class="text-surface-400 text-sm leading-relaxed">Clases grabadas en alta calidad. Accede desde cualquier dispositivo, a tu ritmo, con reproducción fluida vía CDN global.</p>
                </div>

                {{-- Feature Card 5 --}}
                <div class="group p-8 bg-surface-800/40 hover:bg-surface-800/70 border border-surface-700/50 hover:border-ami-500/30 rounded-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 flex items-center justify-center bg-ami-500/10 text-ami-400 rounded-xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3">Gestión de Riesgo</h3>
                    <p class="text-surface-400 text-sm leading-relaxed">El pilar más importante. Te enseñamos a proteger tu capital primero, y luego a hacer crecer tu cuenta de forma consistente.</p>
                </div>

                {{-- Feature Card 6 --}}
                <div class="group p-8 bg-surface-800/40 hover:bg-surface-800/70 border border-surface-700/50 hover:border-ami-500/30 rounded-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 flex items-center justify-center bg-ami-500/10 text-ami-400 rounded-xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3">Plataforma Moderna</h3>
                    <p class="text-surface-400 text-sm leading-relaxed">Tecnología de última generación. Interfaz intuitiva con tema oscuro profesional, pensada para traders que exigen lo mejor.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-ami-950/50 via-ami-900/30 to-ami-950/50"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                Tu futuro como trader<br>
                <span class="text-ami-400">comienza hoy</span>
            </h2>
            <p class="mt-6 text-lg text-surface-300 max-w-2xl mx-auto">
                Únete a Alpha Markets Institute y accede a la formación que realmente necesitas para operar en los mercados con confianza.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}"
                   class="group inline-flex items-center gap-2 px-10 py-4 text-base font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-300 shadow-xl shadow-ami-500/25 hover:shadow-ami-500/40 hover:-translate-y-0.5">
                    Registrarme Gratis
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 px-10 py-4 text-base font-medium text-surface-300 hover:text-white border border-surface-700 hover:border-surface-500 rounded-xl transition-all duration-300">
                    Contactar
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
