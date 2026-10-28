<?php

use App\Services\RoleAndPermissionService;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration {

    use \App\Traits\MigrationTrait;

    public function up(): void
    {
        $guardName = config('backpack.base.guard');
        $timestamps = ['created_at' => now(), 'updated_at' => now()];

        // Roles
        DB::table(config('permission.table_names')['roles'])
            ->insert(array_map(fn (string $role) => ['name' => $role, 'guard_name' => $guardName, ...$timestamps],
                RoleAndPermissionService::ROLES));

        // Permissions
        DB::table(config('permission.table_names')['permissions'])
            ->insert(array_map(fn (string $permission) => ['name' => $permission, 'guard_name' => $guardName, ...$timestamps],
                [
                    ...RoleAndPermissionService::PERMISSIONS_ARTICLE,
                    ...RoleAndPermissionService::PERMISSIONS_CATEGORY,
                    ...RoleAndPermissionService::PERMISSIONS_TAG,
                    ...RoleAndPermissionService::PERMISSIONS_AUTHOR,
                    ...RoleAndPermissionService::PERMISSIONS_CUSTOMER,
                    ...RoleAndPermissionService::PERMISSIONS_ADMIN,
                    ...RoleAndPermissionService::PERMISSIONS_SETTING
                ],
            ));

        $superAdminRole = Role::findByName(RoleAndPermissionService::ROLE_SUPERADMIN, $guardName);
        $adminRole = Role::findByName(RoleAndPermissionService::ROLE_ADMIN, $guardName);
        $editorRole = Role::findByName(RoleAndPermissionService::ROLE_EDITOR, $guardName);
        $viewerRole = Role::findByName(RoleAndPermissionService::ROLE_VIEWER, $guardName);

        // SUPERADMIN - only once!
        $this->addRoleHasPermissions($superAdminRole, Permission::query()->get()->pluck('id')->toArray());

        /**
         *
         * Article permissions by role
         *
         */
        $articlePermissionToViewId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_ARTICLE_READ);
        $articlePermissionToCreateId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_ARTICLE_CREATE);
        $articlePermissionToUpdateId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_ARTICLE_UPDATE);
        $articlePermissionToDeleteId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_ARTICLE_DELETE);

        // ADMIN
        $this->addRoleHasPermissions($adminRole, [
            $articlePermissionToViewId,
            $articlePermissionToCreateId,
            $articlePermissionToUpdateId,
            $articlePermissionToDeleteId,
        ]);

        // EDITOR
        $this->addRoleHasPermissions($editorRole, [
            $articlePermissionToViewId,
            $articlePermissionToCreateId,
            $articlePermissionToUpdateId,
            $articlePermissionToDeleteId,
        ]);

        // VIEWER
        $this->addRoleHasPermissions($viewerRole, [$articlePermissionToViewId]);

        /**
         *
         * Category permissions by role
         *
         */
        $categoryPermissionToViewId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_CATEGORY_READ);
        $categoryPermissionToCreateId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_CATEGORY_CREATE);
        $categoryPermissionToUpdateId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_CATEGORY_UPDATE);
        $categoryPermissionToDeleteId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_CATEGORY_DELETE);

        // ADMIN
        $this->addRoleHasPermissions($adminRole, [
            $categoryPermissionToViewId,
            $categoryPermissionToCreateId,
            $categoryPermissionToUpdateId,
            $categoryPermissionToDeleteId,
        ]);

        // EDITOR
        $this->addRoleHasPermissions($editorRole, [
            $categoryPermissionToViewId,
            $categoryPermissionToCreateId,
            $categoryPermissionToUpdateId,
            $categoryPermissionToDeleteId,
        ]);

        // VIEWER
        $this->addRoleHasPermissions($viewerRole, [$categoryPermissionToViewId]);

        /**
         *
         * Tag permissions by role
         *
         */
        $tagPermissionToViewId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_TAG_READ);
        $tagPermissionToCreateId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_TAG_CREATE);
        $tagPermissionToUpdateId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_TAG_UPDATE);
        $tagPermissionToDeleteId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_TAG_DELETE);

        // ADMIN
        $this->addRoleHasPermissions($adminRole, [
            $tagPermissionToViewId,
            $tagPermissionToCreateId,
            $tagPermissionToUpdateId,
            $tagPermissionToDeleteId,
        ]);

        // EDITOR
        $this->addRoleHasPermissions($editorRole, [
            $tagPermissionToViewId,
            $tagPermissionToCreateId,
            $tagPermissionToUpdateId,
            $tagPermissionToDeleteId,
        ]);

        // VIEWER
        $this->addRoleHasPermissions($viewerRole, [$tagPermissionToViewId]);

        /**
         *
         * Author permissions by role
         *
         */
        $authorPermissionToView = $this->getPermissionId(RoleAndPermissionService::PERMISSION_AUTHOR_READ);
        $authorPermissionToCreate = $this->getPermissionId(RoleAndPermissionService::PERMISSION_AUTHOR_CREATE);
        $authorPermissionToUpdate = $this->getPermissionId(RoleAndPermissionService::PERMISSION_AUTHOR_UPDATE);
        $authorPermissionToDelete = $this->getPermissionId(RoleAndPermissionService::PERMISSION_AUTHOR_DELETE);

        // ADMIN
        $this->addRoleHasPermissions($adminRole, [
            $authorPermissionToView,
            $authorPermissionToCreate,
            $authorPermissionToUpdate,
            $authorPermissionToDelete,
        ]);

        // EDITOR
        $this->addRoleHasPermissions($editorRole, [
            $authorPermissionToView,
            $authorPermissionToCreate,
            $authorPermissionToUpdate,
            $authorPermissionToDelete,
        ]);

        // VIEWER
        $this->addRoleHasPermissions($viewerRole, [$authorPermissionToView]);

        /**
         *
         * Customer permissions by role
         *
         */
        $customerPermissionToViewId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_CUSTOMER_READ);
        $customerPermissionToUpdateId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_CUSTOMER_UPDATE);
        $customerPermissionToDeleteId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_CUSTOMER_DELETE);

        // ADMIN
        $this->addRoleHasPermissions($adminRole, [
            $customerPermissionToViewId,
            $customerPermissionToUpdateId,
            $customerPermissionToDeleteId,
        ]);

        // Setting permissions by role
        $settingPermissionToViewId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_SETTING_READ);
        $settingPermissionToUpdateId = $this->getPermissionId(RoleAndPermissionService::PERMISSION_SETTING_UPDATE);

        // ADMIN
        $this->addRoleHasPermissions($adminRole, [$settingPermissionToViewId, $settingPermissionToUpdateId]);
    }

    public function down(): void
    {
        DB::table(config('permission.table_names')['roles'])
            ->where('guard_name', config('backpack.base.guard'))
            ->whereIn('name', RoleAndPermissionService::ROLES)
            ->delete();

        DB::table(config('permission.table_names')['permissions'])
            ->where('guard_name', config('backpack.base.guard'))
            ->whereIn('name', RoleAndPermissionService::PERMISSIONS)
            ->delete();
    }
};
