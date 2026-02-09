<x-layouts.app>
    <x-slot:title>Contacto</x-slot:title>

    <section class="pt-32 pb-24 relative">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                {{-- Info --}}
                <div>
                    <span class="text-ami-400 text-sm font-semibold uppercase tracking-wider">Contacto</span>
                    <h1 class="mt-4 text-4xl sm:text-5xl font-bold text-white leading-tight">
                        Hablemos sobre<br><span class="text-ami-400">tu formación</span>
                    </h1>
                    <p class="mt-6 text-lg text-surface-300 leading-relaxed">
                        Tienes preguntas sobre nuestros cursos, membresías o metodología? Estamos aquí para ayudarte.
                    </p>

                    <div class="mt-10 space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 flex items-center justify-center bg-ami-500/10 text-ami-400 rounded-lg shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-white">Email</h3>
                                <p class="text-surface-400 text-sm mt-1">contacto@alphamarketsinstitute.com</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 flex items-center justify-center bg-ami-500/10 text-ami-400 rounded-lg shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-white">Horario de atención</h3>
                                <p class="text-surface-400 text-sm mt-1">Lunes a Viernes, 9:00 AM - 6:00 PM (COT)</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <div class="bg-surface-800/40 border border-surface-700/50 rounded-2xl p-8">
                    <form action="#" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-surface-300 mb-2">Nombre completo</label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-3 bg-surface-900/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-surface-300 mb-2">Correo electrónico</label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-3 bg-surface-900/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-surface-300 mb-2">Asunto</label>
                            <select id="subject" name="subject"
                                    class="w-full px-4 py-3 bg-surface-900/60 border border-surface-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all">
                                <option value="cursos">Información sobre cursos</option>
                                <option value="membresias">Membresías</option>
                                <option value="soporte">Soporte técnico</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-surface-300 mb-2">Mensaje</label>
                            <textarea id="message" name="message" rows="4" required
                                      class="w-full px-4 py-3 bg-surface-900/60 border border-surface-700 rounded-xl text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-ami-500/50 focus:border-ami-500 transition-all resize-none"></textarea>
                        </div>
                        <button type="submit"
                                class="w-full py-3.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-300 shadow-lg shadow-ami-500/25 hover:shadow-ami-500/40">
                            Enviar Mensaje
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
