<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RoleSeeder::class);

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

        $this->call(AchievementSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(TradePairSeeder::class);
        $this->call(ManualTradeSeeder::class);
        $this->call(TradeEntrySeeder::class);
        $this->call(JournalSummarySeeder::class);
    }
}
