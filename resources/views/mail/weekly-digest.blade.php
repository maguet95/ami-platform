<x-mail::message>
# Tu Resumen Semanal en AMI

Hola {{ $user->name }},

Aqui esta tu resumen de la ultima semana:

<x-mail::panel>
**Lecciones completadas:** {{ $lessonsCompleted }}
**XP ganados:** +{{ $xpEarned }}
**Racha actual:** {{ $currentStreak }} {{ $currentStreak === 1 ? 'dia' : 'dias' }}
**Logros desbloqueados:** {{ $achievementsUnlocked }}
</x-mail::panel>

@if($lessonsCompleted > 0)
Gran trabajo esta semana. La constancia es lo que separa a los traders exitosos del resto.
@else
No completaste lecciones esta semana. Recuerda que la constancia es clave para desarrollar tu criterio como trader.
@endif

<x-mail::button :url="url('/student/courses')">
Continuar Aprendiendo
</x-mail::button>

Nos vemos la proxima semana,<br>
**Equipo AMI**
</x-mail::message>
