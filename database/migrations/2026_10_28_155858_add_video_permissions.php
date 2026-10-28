<?php

use App\Services\RoleAndPermissionService;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    use \App\Traits\MigrationTrait;

    public function up(): void
    {
        $guardName = config('backpack.base.guard');
        $timestamps = ['created_at' => now(), 'updated_at' => now()];
        $superAdminRole = Role::findByName(RoleAndPermissionService::ROLE_SUPERADMIN, $guardName);
        $adminRole = Role::findByName(RoleAndPermissionService::ROLE_ADMIN, $guardName);
        $editorRole = Role::findByName(RoleAndPermissionService::ROLE_EDITOR, $guardName);
        $viewerRole = Role::findByName(RoleAndPermissionService::ROLE_VIEWER, $guardName);

        DB::table(config('permission.table_names')['permissions'])
            ->insert(array_map(fn (string $permission) => ['name' => $permission, 'guard_name' => $guardName, ...$timestamps],
                RoleAndPermissionService::PERMISSIONS_VIDEO));

        $this->addRoleHasPermissions($superAdminRole, Permission::query()
            ->whereIn('name', RoleAndPermissionService::PERMISSIONS_VIDEO)
            ->where('guard_name', $guardName)
            ->get()
            ->pluck('id')
            ->toArray()
        );

        $this->addRoleHasPermissions($adminRole, Permission::query()
            ->whereIn('name', RoleAndPermissionService::PERMISSIONS_VIDEO)
            ->where('guard_name', $guardName)
            ->get()
            ->pluck('id')
            ->toArray()
        );

        $this->addRoleHasPermissions($editorRole, Permission::query()
            ->whereIn('name', RoleAndPermissionService::PERMISSIONS_VIDEO)
            ->where('guard_name', $guardName)
            ->get()
            ->pluck('id')
            ->toArray()
        );

        $this->addRoleHasPermissions($viewerRole, Permission::query()
            ->where('name', RoleAndPermissionService::PERMISSION_VIDEO_READ)
            ->where('guard_name', $guardName)
            ->get()
            ->pluck('id')
            ->toArray()
        );
    }

    public function down(): void
    {
        $guardName = config('backpack.base.guard');

        DB::table(config('permission.table_names')['permissions'])
            ->whereIn('name', RoleAndPermissionService::PERMISSIONS_VIDEO)
            ->where('guard_name', $guardName)
            ->delete();
    }
};
