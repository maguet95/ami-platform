<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $instructor = Role::firstOrCreate(['name' => 'instructor']);
        $student = Role::firstOrCreate(['name' => 'student']);

        $permissions = [
            'manage users',
            'manage courses',
            'manage enrollments',
            'manage payments',
            'manage content',
            'view dashboard',
            'create courses',
            'edit courses',
            'view courses',
            'view journal',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin->syncPermissions($permissions);

        $instructor->syncPermissions([
            'create courses',
            'edit courses',
            'view courses',
            'view dashboard',
        ]);

        $student->syncPermissions([
            'view courses',
            'view journal',
        ]);
    }
}
