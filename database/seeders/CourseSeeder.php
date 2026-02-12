<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'enmajose95+admin@gmail.com')->first();

        // Course 1: Fundamentos del Trading
        $course1 = Course::create([
            'instructor_id' => $admin?->id,
            'title' => 'Fundamentos del Trading Institucional',
            'slug' => 'fundamentos-trading-institucional',
            'short_description' => 'Aprende las bases del trading profesional con metodología institucional. Desde conceptos básicos hasta análisis de mercado.',
            'description' => '<p>Este curso te guiará desde cero en el mundo del trading profesional. Aprenderás a pensar como las instituciones financieras y a desarrollar un criterio propio basado en análisis técnico y fundamental.</p><p>Diseñado para principiantes que quieren construir una base sólida antes de operar en mercados reales.</p>',
            'level' => 'beginner',
            'price' => 0,
            'is_free' => true,
            'status' => 'published',
            'duration_hours' => 12,
            'sort_order' => 1,
            'is_featured' => true,
            'published_at' => now(),
        ]);

        $mod1 = Module::create(['course_id' => $course1->id, 'title' => 'Introducción al Trading', 'slug' => 'introduccion-trading', 'sort_order' => 1, 'is_published' => true]);
        Lesson::create(['module_id' => $mod1->id, 'title' => '¿Qué es el Trading?', 'slug' => 'que-es-el-trading', 'type' => 'video', 'duration_minutes' => 15, 'sort_order' => 1, 'is_published' => true, 'is_free_preview' => true]);
        Lesson::create(['module_id' => $mod1->id, 'title' => 'Tipos de Mercados Financieros', 'slug' => 'tipos-mercados-financieros', 'type' => 'video', 'duration_minutes' => 20, 'sort_order' => 2, 'is_published' => true]);
        Lesson::create(['module_id' => $mod1->id, 'title' => 'Terminología Esencial', 'slug' => 'terminologia-esencial', 'type' => 'text', 'duration_minutes' => 10, 'sort_order' => 3, 'is_published' => true]);

        $mod2 = Module::create(['course_id' => $course1->id, 'title' => 'Análisis Técnico Básico', 'slug' => 'analisis-tecnico-basico', 'sort_order' => 2, 'is_published' => true]);
        Lesson::create(['module_id' => $mod2->id, 'title' => 'Lectura de Gráficos de Velas', 'slug' => 'lectura-graficos-velas', 'type' => 'video', 'duration_minutes' => 25, 'sort_order' => 1, 'is_published' => true]);
        Lesson::create(['module_id' => $mod2->id, 'title' => 'Soportes y Resistencias', 'slug' => 'soportes-resistencias', 'type' => 'video', 'duration_minutes' => 30, 'sort_order' => 2, 'is_published' => true]);

        $mod3 = Module::create(['course_id' => $course1->id, 'title' => 'Gestión de Riesgo', 'slug' => 'gestion-riesgo', 'sort_order' => 3, 'is_published' => true]);
        Lesson::create(['module_id' => $mod3->id, 'title' => 'Ratio Riesgo/Beneficio', 'slug' => 'ratio-riesgo-beneficio', 'type' => 'video', 'duration_minutes' => 20, 'sort_order' => 1, 'is_published' => true]);
        Lesson::create(['module_id' => $mod3->id, 'title' => 'Tamaño de Posición', 'slug' => 'tamano-posicion', 'type' => 'video', 'duration_minutes' => 18, 'sort_order' => 2, 'is_published' => true]);

        // Course 2: Price Action Avanzado
        $course2 = Course::create([
            'instructor_id' => $admin?->id,
            'title' => 'Price Action Avanzado',
            'slug' => 'price-action-avanzado',
            'short_description' => 'Domina el Price Action con técnicas institucionales. Order blocks, liquidity zones y estructura de mercado.',
            'description' => '<p>Profundiza en el análisis de Price Action utilizado por instituciones financieras. Aprenderás a identificar zonas de liquidez, order blocks y patrones avanzados de estructura de mercado.</p>',
            'level' => 'advanced',
            'price' => 197.00,
            'status' => 'published',
            'duration_hours' => 24,
            'sort_order' => 2,
            'is_featured' => true,
            'published_at' => now(),
        ]);

        $mod4 = Module::create(['course_id' => $course2->id, 'title' => 'Estructura de Mercado', 'slug' => 'estructura-mercado', 'sort_order' => 1, 'is_published' => true]);
        Lesson::create(['module_id' => $mod4->id, 'title' => 'BOS y CHOCH', 'slug' => 'bos-y-choch', 'type' => 'video', 'duration_minutes' => 35, 'sort_order' => 1, 'is_published' => true, 'is_free_preview' => true]);
        Lesson::create(['module_id' => $mod4->id, 'title' => 'Tendencias y Rangos', 'slug' => 'tendencias-y-rangos', 'type' => 'video', 'duration_minutes' => 28, 'sort_order' => 2, 'is_published' => true]);

        $mod5 = Module::create(['course_id' => $course2->id, 'title' => 'Order Blocks', 'slug' => 'order-blocks', 'sort_order' => 2, 'is_published' => true]);
        Lesson::create(['module_id' => $mod5->id, 'title' => 'Identificación de Order Blocks', 'slug' => 'identificacion-order-blocks', 'type' => 'video', 'duration_minutes' => 40, 'sort_order' => 1, 'is_published' => true]);
        Lesson::create(['module_id' => $mod5->id, 'title' => 'Refinamiento y Confluencias', 'slug' => 'refinamiento-confluencias', 'type' => 'video', 'duration_minutes' => 32, 'sort_order' => 2, 'is_published' => true]);

        // Course 3: Psicología del Trading (draft)
        Course::create([
            'instructor_id' => $admin?->id,
            'title' => 'Psicología del Trading',
            'slug' => 'psicologia-del-trading',
            'short_description' => 'Controla tus emociones y desarrolla la mentalidad de un trader profesional.',
            'description' => '<p>La psicología es el factor más importante en el trading. Este curso te enseñará a controlar tus emociones, manejar el estrés y desarrollar disciplina operativa.</p>',
            'level' => 'intermediate',
            'price' => 149.00,
            'status' => 'draft',
            'duration_hours' => 16,
            'sort_order' => 3,
        ]);
    }
}
