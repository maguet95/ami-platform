<nav x-data="{ mobileOpen: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
     :class="(scrolled || mobileOpen) ? 'bg-surface-900/95 backdrop-blur-md shadow-lg shadow-black/20 border-b border-surface-700/50' : 'bg-transparent'"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <img src="{{ asset('images/logos/logo-dark.jpg') }}"
                     alt="AMI - Alpha Markets Institute"
                     class="h-10 lg:h-12 rounded dark:block hidden">
                <img src="{{ asset('images/logos/logo-light.jpg') }}"
                     alt="AMI - Alpha Markets Institute"
                     class="h-10 lg:h-12 rounded dark:hidden block">
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-1">
                <a href="{{ route('home') }}"
                   class="px-4 py-2 text-sm font-medium text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all duration-200">
                    Inicio
                </a>
                <a href="{{ route('about') }}"
                   class="px-4 py-2 text-sm font-medium text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all duration-200">
                    Nosotros
                </a>
                <a href="{{ route('methodology') }}"
                   class="px-4 py-2 text-sm font-medium text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all duration-200">
                    Metodología
                </a>
                <a href="{{ route('courses') }}"
                   class="px-4 py-2 text-sm font-medium text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all duration-200">
                    Cursos
                </a>
                <a href="{{ route('pricing') }}"
                   class="px-4 py-2 text-sm font-medium text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all duration-200">
                    Planes
                </a>
                <a href="{{ route('contact') }}"
                   class="px-4 py-2 text-sm font-medium text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all duration-200">
                    Contacto
                </a>
                <a href="{{ route('ranking') }}"
                   class="px-4 py-2 text-sm font-medium text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all duration-200">
                    Ranking
                </a>
            </div>

            <!-- Right Side: Theme Toggle + CTA -->
            <div class="hidden lg:flex items-center gap-3">
                <!-- Theme Toggle -->
                <button onclick="toggleTheme()"
                        class="p-2 text-surface-400 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all duration-200"
                        title="Cambiar tema">
                    <!-- Sun icon (shown in dark mode) -->
                    <svg class="w-5 h-5 dark:block hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    <!-- Moon icon (shown in light mode) -->
                    <svg class="w-5 h-5 dark:hidden block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-5 py-2.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-lg transition-all duration-200 shadow-lg shadow-ami-500/25 hover:shadow-ami-500/40">
                        Mi Plataforma
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-medium text-surface-300 hover:text-white transition-colors duration-200">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-5 py-2.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-lg transition-all duration-200 shadow-lg shadow-ami-500/25 hover:shadow-ami-500/40">
                        Comenzar Ahora
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileOpen = !mobileOpen"
                    class="lg:hidden p-2 text-surface-400 hover:text-white rounded-lg">
                <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden pb-4 border-t border-surface-700/50 mt-2">
            <div class="pt-4 space-y-1">
                <a href="{{ route('home') }}" class="block px-4 py-3 text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all">Inicio</a>
                <a href="{{ route('about') }}" class="block px-4 py-3 text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all">Nosotros</a>
                <a href="{{ route('methodology') }}" class="block px-4 py-3 text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all">Metodología</a>
                <a href="{{ route('courses') }}" class="block px-4 py-3 text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all">Cursos</a>
                <a href="{{ route('pricing') }}" class="block px-4 py-3 text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all">Planes</a>
                <a href="{{ route('contact') }}" class="block px-4 py-3 text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all">Contacto</a>
                <a href="{{ route('ranking') }}" class="block px-4 py-3 text-surface-300 hover:text-white hover:bg-surface-800/60 rounded-lg transition-all">Ranking</a>
            </div>
            <div class="pt-4 mt-4 border-t border-surface-700/50 space-y-2 px-4">
                <button onclick="toggleTheme()" class="w-full flex items-center gap-2 py-3 text-surface-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    Cambiar tema
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="block w-full text-center py-3 text-white bg-ami-500 hover:bg-ami-600 rounded-lg font-semibold transition-all">Mi Plataforma</a>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center py-3 text-surface-300 hover:text-white rounded-lg border border-surface-700 transition-all">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="block w-full text-center py-3 text-white bg-ami-500 hover:bg-ami-600 rounded-lg font-semibold transition-all">Comenzar Ahora</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
