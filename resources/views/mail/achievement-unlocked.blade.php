<x-mail::message>
# Nuevo Logro Desbloqueado

Hola {{ $user->name }},

Has desbloqueado un nuevo logro en AMI:

<x-mail::panel>
**{{ $achievement->icon }} {{ $achievement->name }}**

{{ $achievement->description }}

**Nivel:** {{ $tierLabel }} | **XP ganados:** +{{ $xp }}
</x-mail::panel>

Sigue asi — cada logro te acerca mas a convertirte en un trader con criterio propio.

<x-mail::button :url="url('/student/achievements')">
Ver Mis Logros
</x-mail::button>

Exitos en tu camino,<br>
**Equipo AMI**
</x-mail::message>
