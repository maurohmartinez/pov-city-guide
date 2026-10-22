<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Services\RoleAndPermissionService;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = Admin::factory()->create([
            'name' => 'Superadmin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
        ]);

        $superadmin->roles()->attach(Role::findByName(RoleAndPermissionService::ROLE_SUPERADMIN, config('backpack.base.guard')));

        $admin = Admin::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $admin->roles()->attach(Role::findByName(RoleAndPermissionService::ROLE_ADMIN, config('backpack.base.guard')));

        $editor = Admin::factory()->create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'password' => bcrypt('password'),
        ]);

        $editor->roles()->attach(Role::findByName(RoleAndPermissionService::ROLE_EDITOR, config('backpack.base.guard')));

        $viewer = Admin::factory()->create([
            'name' => 'Viewer',
            'email' => 'viewer@example.com',
            'password' => bcrypt('password'),
        ]);

        $viewer->roles()->attach(Role::findByName(RoleAndPermissionService::ROLE_VIEWER, config('backpack.base.guard')));
    }
}
