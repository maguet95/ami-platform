<x-mail::message>
# Suscripcion Activada — AMI Premium

Hola {{ $user->name }},

Tu suscripcion ha sido activada exitosamente.

<x-mail::panel>
**Plan:** {{ $planName }}
**Monto:** ${{ $amount }} USD
</x-mail::panel>

**Ahora tienes acceso a:**

- Todos los cursos premium de trading
- Bitacora de trading (Journal)
- Logros y sistema de gamificacion completo
- Contenido exclusivo para miembros

<x-mail::button :url="url('/cursos')">
Explorar Cursos Premium
</x-mail::button>

Gracias por tu confianza,<br>
**Equipo AMI**
</x-mail::message>
