<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // Migrate old admin email if it exists (preserves FK relations)
        User::where('email', 'enmajose95@gmail.com')
            ->update(['email' => 'enmajose95+admin@gmail.com']);

        // Admin user
        $admin = User::updateOrCreate(
            ['email' => 'enmajose95+admin@gmail.com'],
            [
                'name' => 'Jose Enma',
                'password' => '12345',
                'email_verified_at' => now(),
                'username' => 'joseenma',
                'is_profile_public' => true,
            ]
        );
        $admin->assignRole('admin');

        // Instructor user
        $instructor = User::updateOrCreate(
            ['email' => 'enmajose95+instructor@gmail.com'],
            [
                'name' => 'Jose Enma (Instructor)',
                'password' => '12345',
                'email_verified_at' => now(),
                'username' => 'profesor-enmanuel',
                'is_profile_public' => true,
            ]
        );
        $instructor->assignRole('instructor');

        // Seed test data only if no courses exist (safe for re-deploys)
        if (Course::count() === 0) {
            $this->call([
                CourseSeeder::class,
                PlanSeeder::class,
                TradePairSeeder::class,
                ManualTradeSeeder::class,
                TradeEntrySeeder::class,
                JournalSummarySeeder::class,
            ]);
        }
    }
}
