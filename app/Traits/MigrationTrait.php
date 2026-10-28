<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait MigrationTrait
{
    private function addRoleHasPermissions(Role $role, array $permissionsId): void
    {
        array_map(fn (int $permissionId) => DB::table(config('permission.table_names')['role_has_permissions'])
            ->insert(['permission_id' => $permissionId, 'role_id' => $role->id]),
            $permissionsId);
    }

    private function getPermissionId(string $permission): int
    {
        return Permission::findByName($permission, config('backpack.base.guard'))->id;
    }
}
