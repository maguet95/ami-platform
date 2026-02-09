<x-layouts.app>
    <x-slot:title>Política de Privacidad</x-slot:title>

    <section class="pt-32 pb-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-ami-400 text-sm font-semibold uppercase tracking-wider">Legal</span>
            <h1 class="mt-4 text-4xl font-bold text-white">Política de Privacidad</h1>
            <p class="mt-4 text-surface-400">Última actualización: {{ date('d/m/Y') }}</p>

            <div class="mt-12 space-y-8">
                <div class="p-6 bg-surface-800/40 border border-surface-700/50 rounded-xl">
                    <h2 class="text-xl font-semibold text-white mb-4">1. Información que Recopilamos</h2>
                    <p class="text-surface-300 leading-relaxed">
                        Recopilamos información que nos proporcionas directamente: nombre, correo electrónico, datos de pago
                        y cualquier información que compartas en formularios de contacto o en tu perfil de usuario.
                    </p>
                </div>

                <div class="p-6 bg-surface-800/40 border border-surface-700/50 rounded-xl">
                    <h2 class="text-xl font-semibold text-white mb-4">2. Uso de la Información</h2>
                    <p class="text-surface-300 leading-relaxed">
                        Utilizamos tu información para: proporcionar y mejorar nuestros servicios educativos,
                        procesar pagos, enviar comunicaciones relevantes sobre cursos y actualizaciones,
                        y personalizar tu experiencia de aprendizaje.
                    </p>
                </div>

                <div class="p-6 bg-surface-800/40 border border-surface-700/50 rounded-xl">
                    <h2 class="text-xl font-semibold text-white mb-4">3. Protección de Datos</h2>
                    <p class="text-surface-300 leading-relaxed">
                        Implementamos medidas de seguridad técnicas y organizativas para proteger tu información personal.
                        Los datos de pago son procesados de forma segura a través de Stripe y nunca almacenamos
                        información de tarjetas de crédito en nuestros servidores.
                    </p>
                </div>

                <div class="p-6 bg-surface-800/40 border border-surface-700/50 rounded-xl">
                    <h2 class="text-xl font-semibold text-white mb-4">4. Tus Derechos</h2>
                    <p class="text-surface-300 leading-relaxed">
                        Tienes derecho a acceder, rectificar, eliminar o portar tus datos personales.
                        Para ejercer estos derechos, contáctanos a contacto@alphamarketsinstitute.com.
                    </p>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
