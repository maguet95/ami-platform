<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $achievements = [
            ['slug' => 'primera-leccion', 'name' => 'Primera Leccion', 'description' => 'Completaste tu primera leccion. El viaje comienza aqui.', 'icon' => 'heroicon-o-play', 'category' => 'learning', 'xp_reward' => 10, 'requirement_type' => 'lessons_completed', 'requirement_value' => 1, 'tier' => 'bronze', 'sort_order' => 1],
            ['slug' => 'estudiante-dedicado', 'name' => 'Estudiante Dedicado', 'description' => 'Has completado 10 lecciones. Tu disciplina se nota.', 'icon' => 'heroicon-o-book-open', 'category' => 'learning', 'xp_reward' => 25, 'requirement_type' => 'lessons_completed', 'requirement_value' => 10, 'tier' => 'silver', 'sort_order' => 2],
            ['slug' => 'devorador-de-conocimiento', 'name' => 'Devorador de Conocimiento', 'description' => '50 lecciones completadas. Estas construyendo criterio real.', 'icon' => 'heroicon-o-fire', 'category' => 'learning', 'xp_reward' => 100, 'requirement_type' => 'lessons_completed', 'requirement_value' => 50, 'tier' => 'gold', 'sort_order' => 3],
            ['slug' => 'primer-curso', 'name' => 'Primer Curso Completado', 'description' => 'Terminaste tu primer curso completo. Proceso > resultados rapidos.', 'icon' => 'heroicon-o-academic-cap', 'category' => 'learning', 'xp_reward' => 50, 'requirement_type' => 'courses_completed', 'requirement_value' => 1, 'tier' => 'bronze', 'sort_order' => 4],
            ['slug' => 'trader-formado', 'name' => 'Trader en Formacion', 'description' => '3 cursos completados. Tu base de conocimiento crece.', 'icon' => 'heroicon-o-chart-bar', 'category' => 'learning', 'xp_reward' => 150, 'requirement_type' => 'courses_completed', 'requirement_value' => 3, 'tier' => 'silver', 'sort_order' => 5],
            ['slug' => 'maestro-del-mercado', 'name' => 'Maestro del Mercado', 'description' => '5 cursos completados. Criterio > senales.', 'icon' => 'heroicon-o-trophy', 'category' => 'learning', 'xp_reward' => 300, 'requirement_type' => 'courses_completed', 'requirement_value' => 5, 'tier' => 'gold', 'sort_order' => 6],
            ['slug' => 'racha-7', 'name' => 'Racha Semanal', 'description' => '7 dias consecutivos de estudio. La consistencia gana.', 'icon' => 'heroicon-o-bolt', 'category' => 'engagement', 'xp_reward' => 50, 'requirement_type' => 'login_streak', 'requirement_value' => 7, 'tier' => 'bronze', 'sort_order' => 7],
            ['slug' => 'racha-30', 'name' => 'Racha Mensual', 'description' => '30 dias consecutivos. Disciplina de trader profesional.', 'icon' => 'heroicon-o-bolt', 'category' => 'engagement', 'xp_reward' => 200, 'requirement_type' => 'login_streak', 'requirement_value' => 30, 'tier' => 'gold', 'sort_order' => 8],
            ['slug' => 'racha-100', 'name' => 'Racha Legendaria', 'description' => '100 dias consecutivos. Eres leyenda.', 'icon' => 'heroicon-o-star', 'category' => 'engagement', 'xp_reward' => 500, 'requirement_type' => 'login_streak', 'requirement_value' => 100, 'tier' => 'diamond', 'sort_order' => 9],
            ['slug' => 'xp-100', 'name' => 'Primer Centenar', 'description' => 'Alcanzaste 100 XP. Nivel 2 desbloqueado.', 'icon' => 'heroicon-o-sparkles', 'category' => 'milestone', 'xp_reward' => 10, 'requirement_type' => 'total_xp', 'requirement_value' => 100, 'tier' => 'bronze', 'sort_order' => 10],
            ['slug' => 'xp-500', 'name' => 'Medio Millar', 'description' => '500 XP acumulados. Tu esfuerzo se refleja.', 'icon' => 'heroicon-o-sparkles', 'category' => 'milestone', 'xp_reward' => 25, 'requirement_type' => 'total_xp', 'requirement_value' => 500, 'tier' => 'silver', 'sort_order' => 11],
            ['slug' => 'xp-1000', 'name' => 'Club del Millar', 'description' => '1000 XP. Estas en la elite de AMI.', 'icon' => 'heroicon-o-sparkles', 'category' => 'milestone', 'xp_reward' => 50, 'requirement_type' => 'total_xp', 'requirement_value' => 1000, 'tier' => 'gold', 'sort_order' => 12],
            ['slug' => 'xp-5000', 'name' => 'Leyenda AMI', 'description' => '5000 XP. Eres inspiracion para la comunidad.', 'icon' => 'heroicon-o-trophy', 'category' => 'milestone', 'xp_reward' => 100, 'requirement_type' => 'total_xp', 'requirement_value' => 5000, 'tier' => 'diamond', 'sort_order' => 13],
        ];

        $now = now();

        foreach ($achievements as $data) {
            DB::table('achievements')->updateOrInsert(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }

    public function down(): void
    {
        DB::table('achievements')->whereIn('slug', [
            'primera-leccion', 'estudiante-dedicado', 'devorador-de-conocimiento',
            'primer-curso', 'trader-formado', 'maestro-del-mercado',
            'racha-7', 'racha-30', 'racha-100',
            'xp-100', 'xp-500', 'xp-1000', 'xp-5000',
        ])->delete();
    }
};
