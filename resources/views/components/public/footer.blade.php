<footer class="bg-surface-900 border-t border-surface-700/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Main Footer -->
        <div class="py-12 lg:py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            <!-- Brand -->
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('images/logos/logo-dark.jpg') }}"
                         alt="AMI" class="h-10 rounded dark:block hidden">
                    <img src="{{ asset('images/logos/logo-light.jpg') }}"
                         alt="AMI" class="h-10 rounded dark:hidden block">
                </a>
                <p class="mt-4 text-sm text-surface-400 leading-relaxed max-w-xs">
                    Alpha Markets Institute. Formamos traders profesionales con metodología institucional y tecnología de vanguardia.
                </p>
                <!-- Social -->
                <div class="mt-6 flex items-center gap-3">
                    <a href="#" class="p-2 text-surface-500 hover:text-ami-400 hover:bg-surface-800 rounded-lg transition-all duration-200" aria-label="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="#" class="p-2 text-surface-500 hover:text-ami-400 hover:bg-surface-800 rounded-lg transition-all duration-200" aria-label="YouTube">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <a href="#" class="p-2 text-surface-500 hover:text-ami-400 hover:bg-surface-800 rounded-lg transition-all duration-200" aria-label="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#" class="p-2 text-surface-500 hover:text-ami-400 hover:bg-surface-800 rounded-lg transition-all duration-200" aria-label="Telegram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.479.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Plataforma</h3>
                <ul class="mt-4 space-y-3">
                    <li><a href="{{ route('courses') }}" class="text-sm text-surface-400 hover:text-ami-400 transition-colors duration-200">Cursos</a></li>
                    <li><a href="{{ route('methodology') }}" class="text-sm text-surface-400 hover:text-ami-400 transition-colors duration-200">Metodología</a></li>
                    <li><a href="#" class="text-sm text-surface-400 hover:text-ami-400 transition-colors duration-200">Membresías</a></li>
                    <li><a href="#" class="text-sm text-surface-400 hover:text-ami-400 transition-colors duration-200">Trading Journal</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Compañía</h3>
                <ul class="mt-4 space-y-3">
                    <li><a href="{{ route('about') }}" class="text-sm text-surface-400 hover:text-ami-400 transition-colors duration-200">Sobre Nosotros</a></li>
                    <li><a href="{{ route('contact') }}" class="text-sm text-surface-400 hover:text-ami-400 transition-colors duration-200">Contacto</a></li>
                    <li><a href="#" class="text-sm text-surface-400 hover:text-ami-400 transition-colors duration-200">Blog</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Legal</h3>
                <ul class="mt-4 space-y-3">
                    <li><a href="{{ route('terms') }}" class="text-sm text-surface-400 hover:text-ami-400 transition-colors duration-200">Términos y Condiciones</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-sm text-surface-400 hover:text-ami-400 transition-colors duration-200">Política de Privacidad</a></li>
                    <li><a href="#" class="text-sm text-surface-400 hover:text-ami-400 transition-colors duration-200">Aviso de Riesgo</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="py-6 border-t border-surface-800 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-surface-500">
                &copy; {{ date('Y') }} Alpha Markets Institute. Todos los derechos reservados.
            </p>
            <p class="text-xs text-surface-600">
                El trading implica riesgos. Los resultados pasados no garantizan rendimientos futuros.
            </p>
        </div>
    </div>
</footer>
