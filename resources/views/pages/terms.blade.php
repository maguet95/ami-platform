<x-layouts.app>
    <x-slot:title>Términos y Condiciones</x-slot:title>

    <section class="pt-32 pb-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-ami-400 text-sm font-semibold uppercase tracking-wider">Legal</span>
            <h1 class="mt-4 text-4xl font-bold text-white">Términos y Condiciones</h1>
            <p class="mt-4 text-surface-400">Última actualización: {{ date('d/m/Y') }}</p>

            <div class="mt-12 prose prose-invert prose-surface max-w-none space-y-8">
                <div class="p-6 bg-surface-800/40 border border-surface-700/50 rounded-xl">
                    <h2 class="text-xl font-semibold text-white mb-4">1. Aceptación de Términos</h2>
                    <p class="text-surface-300 leading-relaxed">
                        Al acceder y utilizar la plataforma de Alpha Markets Institute (AMI), aceptas estos términos y condiciones en su totalidad.
                        Si no estás de acuerdo con alguno de estos términos, no debes utilizar nuestros servicios.
                    </p>
                </div>

                <div class="p-6 bg-surface-800/40 border border-surface-700/50 rounded-xl">
                    <h2 class="text-xl font-semibold text-white mb-4">2. Servicios Educativos</h2>
                    <p class="text-surface-300 leading-relaxed">
                        AMI proporciona contenido educativo sobre trading y mercados financieros. Nuestros cursos, materiales y herramientas
                        son de naturaleza educativa y no constituyen asesoría financiera, recomendaciones de inversión, ni garantía de resultados.
                    </p>
                </div>

                <div class="p-6 bg-surface-800/40 border border-surface-700/50 rounded-xl">
                    <h2 class="text-xl font-semibold text-white mb-4">3. Aviso de Riesgo</h2>
                    <p class="text-surface-300 leading-relaxed">
                        El trading en mercados financieros conlleva un alto nivel de riesgo y puede no ser adecuado para todos los inversores.
                        Los resultados pasados no garantizan rendimientos futuros. Podrías perder parte o la totalidad de tu inversión.
                        Opera únicamente con capital que puedas permitirte perder.
                    </p>
                </div>

                <div class="p-6 bg-surface-800/40 border border-surface-700/50 rounded-xl">
                    <h2 class="text-xl font-semibold text-white mb-4">4. Propiedad Intelectual</h2>
                    <p class="text-surface-300 leading-relaxed">
                        Todo el contenido de la plataforma — cursos, videos, textos, gráficos y materiales — es propiedad de AMI
                        y está protegido por leyes de derechos de autor. No se permite la reproducción, distribución o uso comercial
                        sin autorización expresa.
                    </p>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
