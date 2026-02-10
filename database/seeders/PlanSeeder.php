<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::firstOrCreate(
            ['slug' => 'mensual'],
            [
                'name' => 'Mensual',
                'description' => 'Acceso completo a todos los cursos premium con facturación mensual.',
                'stripe_product_id' => null,
                'stripe_price_id' => null,
                'price' => 29.99,
                'currency' => 'USD',
                'interval' => 'monthly',
                'features' => [
                    'Acceso a todos los cursos premium',
                    'Nuevos contenidos cada semana',
                    'Soporte por comunidad',
                    'Cancela cuando quieras',
                ],
                'sort_order' => 1,
                'is_active' => true,
                'is_featured' => false,
            ]
        );

        Plan::firstOrCreate(
            ['slug' => 'anual'],
            [
                'name' => 'Anual',
                'description' => 'Acceso completo a todos los cursos premium con facturación anual. Ahorra un 30%.',
                'stripe_product_id' => null,
                'stripe_price_id' => null,
                'price' => 249.99,
                'currency' => 'USD',
                'interval' => 'yearly',
                'features' => [
                    'Todo lo del plan Mensual',
                    'Ahorro del 30% vs mensual',
                    'Acceso prioritario a nuevo contenido',
                    'Sesiones grupales mensuales',
                ],
                'sort_order' => 2,
                'is_active' => true,
                'is_featured' => true,
            ]
        );
    }
}
