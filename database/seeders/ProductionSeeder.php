<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $admin = User::firstOrCreate(
            ['email' => 'enmajose95@gmail.com'],
            [
                'name' => 'Jose Enma',
                'password' => bcrypt('12345'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');
    }
}
