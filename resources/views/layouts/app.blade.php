<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AMI') }} — Plataforma</title>
    <link rel="icon" href="{{ asset('images/logos/isotipo.jpg') }}" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface-950 text-surface-100 antialiased">
    <div class="min-h-screen flex">

        {{-- Sidebar --}}
        <aside x-data="{ collapsed: false, mobileOpen: false }"
               x-on:toggle-sidebar.window="mobileOpen = !mobileOpen"
               class="flex flex-col shrink-0">

            {{-- Mobile Overlay --}}
            <div x-show="mobileOpen"
                 x-cloak
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileOpen = false"
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden">
            </div>

            {{-- Sidebar Panel --}}
            <div :class="mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                 class="fixed lg:sticky top-0 left-0 z-50 h-screen flex flex-col bg-surface-900 border-r border-surface-700/50 transition-transform duration-300 ease-in-out"
                 :style="collapsed ? 'width: 4.5rem' : 'width: 16rem'">

                {{-- Logo --}}
                <div class="flex items-center justify-between h-16 px-4 border-b border-surface-700/50">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden">
                        <img src="{{ asset('images/logos/isotipo.jpg') }}" alt="AMI" class="h-8 w-8 rounded shrink-0">
                        <span x-show="!collapsed" x-cloak class="text-lg font-bold text-white whitespace-nowrap">AMI</span>
                    </a>
                    <button @click="collapsed = !collapsed"
                            class="hidden lg:flex p-1.5 text-surface-400 hover:text-white hover:bg-surface-800 rounded-lg transition-all">
                        <svg x-show="!collapsed" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                        <svg x-show="collapsed" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <button @click="mobileOpen = false" class="lg:hidden p-1.5 text-surface-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('dashboard') ? 'bg-ami-500/10 text-ami-400' : 'text-surface-400 hover:text-white hover:bg-surface-800/60' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                        <span x-show="!collapsed" x-cloak>Dashboard</span>
                    </a>

                    {{-- Mis Cursos --}}
                    <a href="{{ route('student.courses') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('student.*') ? 'bg-ami-500/10 text-ami-400' : 'text-surface-400 hover:text-white hover:bg-surface-800/60' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                        </svg>
                        <span x-show="!collapsed" x-cloak>Mis Cursos</span>
                    </a>

                    {{-- Catálogo --}}
                    <a href="{{ route('courses') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 text-surface-400 hover:text-white hover:bg-surface-800/60">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                        <span x-show="!collapsed" x-cloak>Catálogo de Cursos</span>
                    </a>

                    <div x-show="!collapsed" x-cloak class="pt-4 pb-2 px-3">
                        <p class="text-[11px] font-semibold text-surface-600 uppercase tracking-wider">Cuenta</p>
                    </div>

                    {{-- Perfil --}}
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('profile.*') ? 'bg-ami-500/10 text-ami-400' : 'text-surface-400 hover:text-white hover:bg-surface-800/60' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span x-show="!collapsed" x-cloak>Mi Perfil</span>
                    </a>
                </nav>

                {{-- User Area --}}
                <div class="border-t border-surface-700/50 p-3">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm text-surface-300 hover:text-white hover:bg-surface-800/60 transition-all duration-200">
                            <div class="w-8 h-8 rounded-full bg-ami-500/20 flex items-center justify-center shrink-0">
                                <span class="text-xs font-semibold text-ami-400">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div x-show="!collapsed" x-cloak class="flex-1 text-left overflow-hidden">
                                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-surface-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <svg x-show="!collapsed" x-cloak class="w-4 h-4 text-surface-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open"
                             x-cloak
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute bottom-full left-0 right-0 mb-2 bg-surface-800 border border-surface-700 rounded-xl shadow-xl shadow-black/30 overflow-hidden">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-surface-300 hover:text-white hover:bg-surface-700/50 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Configuración
                            </a>
                            <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-surface-300 hover:text-white hover:bg-surface-700/50 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                                Ir al Sitio
                            </a>
                            <div class="border-t border-surface-700"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-bearish hover:bg-bearish/10 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                    </svg>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 h-16 bg-surface-950/80 backdrop-blur-md border-b border-surface-700/50 flex items-center justify-between px-4 lg:px-8">
                <div class="flex items-center gap-4">
                    {{-- Mobile hamburger --}}
                    <button @click="$dispatch('toggle-sidebar')"
                            class="lg:hidden p-2 text-surface-400 hover:text-white hover:bg-surface-800 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    {{-- Page Header --}}
                    @isset($header)
                        <div>{{ $header }}</div>
                    @endisset
                </div>

                <div class="flex items-center gap-2">
                    {{-- Theme Toggle --}}
                    <button onclick="toggleTheme()"
                            class="p-2 text-surface-400 hover:text-white hover:bg-surface-800 rounded-lg transition-all duration-200"
                            title="Cambiar tema">
                        <svg class="w-5 h-5 dark:block hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                        <svg class="w-5 h-5 dark:hidden block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                    </button>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-4 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
