import { driver } from 'driver.js';
import 'driver.js/dist/driver.css';

window.startOnboarding = function (completeUrl, csrfToken) {
    const driverObj = driver({
        showProgress: true,
        progressText: '{{current}} de {{total}}',
        nextBtnText: 'Siguiente →',
        prevBtnText: '← Anterior',
        doneBtnText: 'Empezar',
        allowClose: true,
        overlayOpacity: 0.75,
        smoothScroll: true,
        onDestroyStarted: () => {
            markComplete(completeUrl, csrfToken);
            driverObj.destroy();
        },
        steps: [
            {
                popover: {
                    title: '👋 Bienvenido a AMI',
                    description: 'Te damos un recorrido rápido por la plataforma para que aproveches todo al máximo. Puedes saltar en cualquier momento.',
                    side: 'center',
                    align: 'center',
                },
            },
            {
                element: '[data-tour="dashboard"]',
                popover: {
                    title: '📊 Tu Dashboard',
                    description: 'Aquí ves tu resumen: progreso, rachas activas, logros recientes y actividad de la comunidad. Tu punto de partida cada día.',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="cursos"]',
                popover: {
                    title: '🎓 Cursos',
                    description: 'Accede a toda la biblioteca de cursos premium. Videos grabados de sesiones reales de mercado, organizados por módulos.',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="calendario"]',
                popover: {
                    title: '📅 Clases en Vivo',
                    description: 'El calendario de clases en tiempo real. Cada semana hay sesiones interactivas donde puedes ver el mercado en directo y hacer preguntas.',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="journal"]',
                popover: {
                    title: '📈 Trading Journal',
                    description: 'Tu diario de operaciones. Registra tus trades, analiza tu desempeño con estadísticas reales y deja de operar por intuición.',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="logros"]',
                popover: {
                    title: '🏆 Logros y Ranking',
                    description: 'Gana XP completando lecciones, asistiendo a clases y registrando trades. Compite en el ranking y desbloquea logros exclusivos.',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="perfil"]',
                popover: {
                    title: '👤 Tu Perfil',
                    description: 'Personaliza tu perfil público de trader. Comparte tu progreso, logros y estadísticas con la comunidad.',
                    side: 'bottom',
                    align: 'end',
                },
            },
            {
                popover: {
                    title: '🚀 Todo listo',
                    description: 'Ya conoces la plataforma. Si quieres ver este tour de nuevo, encuéntralo en tu Perfil → Configuración. ¡Que empiece el proceso!',
                    side: 'center',
                    align: 'center',
                },
            },
        ],
    });

    driverObj.drive();
};

function markComplete(url, token) {
    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json' },
    }).catch(() => {});
}
